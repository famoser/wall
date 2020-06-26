<template>
    <div>
        <div v-if="authorized" class="mb-2">
            <div class="btn-group-toggle d-inline">
                <label class="btn btn-outline-secondary"
                       :class="{'active': mode === 'edit' }">
                    <input type="checkbox" autocomplete="off" :true-value="'edit'" :false-value="'view'" v-model="mode">
                    <font-awesome-icon :icon="['fal', 'pencil']"></font-awesome-icon>
                </label>
            </div>

            <button class="btn btn-outline-secondary" @click="add" v-if="mode === 'edit'">
                <font-awesome-icon :icon="['fal', 'plus']"></font-awesome-icon>
            </button>

            <div class="btn-group-toggle d-inline">
                <label class="btn btn-outline-secondary float-right"
                       :class="{'active': mode === 'shopping' }">
                    <input type="checkbox" autocomplete="off" :true-value="'shopping'" :false-value="'view'"
                           v-model="mode">
                    <font-awesome-icon :icon="['fal', 'shopping-bag']"></font-awesome-icon>
                </label>
            </div>
        </div>

        <template v-for="(productCategory, index) in productCategories">
            <p class="lead mb-0">{{ $t("products.categories." + productCategory.name) }}</p>
            <div v-for="product in productCategory.products">
                <div v-if="mode === 'view' || mode === 'shopping'"
                     class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input"
                           v-model="product.active"
                           @change="save(product)"
                           :id="product.id"
                           :disabled="!authorized"
                    >
                    <label class="custom-control-label" :for="product.id">{{product.name}}</label>
                </div>
                <div v-if="mode === 'edit'">
                    <div class="btn-group">
                        <button class="btn" @click="edit(product)">
                            <font-awesome-icon class="text-warning" :icon="['fal', 'pencil']"></font-awesome-icon>
                        </button>
                        <button class="btn" @click="remove(product)">
                            <font-awesome-icon class="text-danger" :icon="['fal', 'trash']"></font-awesome-icon>
                        </button>
                    </div>
                    {{product.name}}
                </div>
            </div>
            <div class="mb-4" v-if="index < productCategories.length - 1"></div>
        </template>

        <b-modal id="modal-product-edit" :centered="true" hide-header
                 @ok="confirmEdit">
            <div class="form-group">
                <input type="text" class="form-control form-control-lg" id="name"
                       :placeholder="$t('entity.product.name')"
                       v-model="selected.name">
            </div>
            <div class="form-group">
                <select class="form-control form-control-lg" v-model="selected.category">
                    <option v-for="index in 7" :value="index">
                        {{ $t("products.categories." + categoryToTranslationId(index)) }}
                    </option>
                </select>
            </div>
        </b-modal>

        <b-modal id="modal-product-remove" :centered="true" hide-header
                 @ok="confirmRemove">
            {{ $t("messages.danger.confirm_remove") }}
        </b-modal>
    </div>
</template>

<script>
    import Spinner from "./Spinner";

    const defaultProduct = {
        "name": "",
        "category": 1,
        "active": false
    }

    export default {
        components: {Spinner},
        props: {
            products: {
                type: Array,
                required: true
            },
            authorized: {
                type: Boolean,
                required: true
            }
        },
        data: function () {
            return {
                selected: defaultProduct,
                mode: 'view'
            }
        },
        methods: {
            categoryToTranslationId: function (index) {
                switch (index) {
                    case 1:
                        return "fruit_vegetables";
                    case 2:
                        return "dairy_products_eggs";
                    case 3:
                        return "bread_backed_goods"
                    case 4:
                        return "inventories"
                    case 5:
                        return "frozen_food_ready_made_meals"
                    case 6:
                        return "drinks";
                    case 7:
                        return "household"
                    default:
                        return "other"
                }
            },
            save: function (product) {
                const update = {
                    "name": product.name,
                    "category": product.category,
                    "active": product.active,
                }
                if (product.persistedInDatabase) {
                    this.$emit("patch-product", product.id, update)
                } else {
                    this.$emit("add-product", update);
                }
            },
            confirmEdit: function () {
                this.save(this.selected);
            },
            confirmRemove: function () {
                this.$emit("delete-product", this.selected.id);
            },
            add: function () {
                this.selected = defaultProduct

                this.$bvModal.show("modal-product-edit");
            },
            edit: function (product) {
                this.selected = product

                this.$bvModal.show("modal-product-edit");
            },
            remove: function (product) {
                this.selected = product

                this.$bvModal.show("modal-product-remove");
            }
        },
        computed: {
            productCategories: function () {
                const map = new Map();
                let products = this.products;
                if (this.mode === 'shopping') {
                    products = products.filter(p => p.active);
                }

                products.forEach((product) => {
                    const key = product.category;
                    let category = map.get(key);
                    if (!category) {
                        category = {
                            "name": this.categoryToTranslationId(key),
                            "products": []
                        }
                        map.set(key, category);
                    }
                    category.products.push(product);
                });

                return Array.from(map.keys()).sort().map(key => {
                    let category = map.get(key)
                    category.products = category.products.sort((a, b) => a.name.localeCompare(b.name))
                    return category
                });
            }
        }
    }
</script>
