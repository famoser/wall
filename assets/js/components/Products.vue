<template>
    <div>
        <p v-if="products.length === 0">{{ $t("products.no_products")}}</p>

        <div class="mb-2" v-if="editMode">
            <button class="btn btn-outline-secondary" @click="add">
                <font-awesome-icon :icon="['fal', 'plus']"></font-awesome-icon>
            </button>
        </div>

        <div class="mb-2" v-if="!editMode && authorized && (shoppingMode || someProductsToBuy)" >
            <div class="btn-group-toggle d-inline">
                <label class="btn btn-outline-secondary float-right"
                       :class="{'active': shoppingMode }">
                    <input type="checkbox" autocomplete="off" :true-value="true" :false-value="false"
                           v-model="shoppingMode">
                    <font-awesome-icon :icon="['fal', 'shopping-bag']"></font-awesome-icon>
                </label>
            </div>
        </div>

        <template v-for="(productCategory, index) in productCategories">
            <p class="lead mb-0">{{ $t("products.categories." + productCategory.name) }}</p>
            <div v-for="product in productCategory.products">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input"
                           v-model="product.active"
                           @change="activeChanged(product)"
                           :id="product['@id']"
                           :key="product['@id']"
                           :disabled="!authorized"
                    >
                    <label class="custom-control-label" :for="product['@id']">
                        {{product.name}}
                        <span v-if="editMode">
                                <button class="btn btn-sm" @click="edit(product)">
                                    <font-awesome-icon
                                            class="text-warning"
                                            :icon="['fal', 'pencil']">
                                    </font-awesome-icon>
                                </button>
                                <button class="btn btn-sm" @click="remove(product)">
                                    <font-awesome-icon
                                            class="text-danger"
                                            :icon="['fal', 'trash']">
                                    </font-awesome-icon>
                                </button>
                            </span>
                    </label>
                </div>
            </div>
            <div class="mb-4" v-if="index < productCategories.length - 1"></div>
        </template>

      <p v-if="shoppingMode && !someProductsToBuy" class="lead">
        {{ $t('products.shopping_successful')}}
      </p>

        <b-modal id="modal-product-edit" :centered="true" hide-header no-close-on-backdrop
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

        <b-modal id="modal-product-remove" :centered="true" hide-header no-close-on-backdrop
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
        "active": true
    }

    export default {
        components: {Spinner},
        props: {
            products: {
                type: Array,
                required: true
            },
            editMode: {
                type: Boolean,
                required: true
            },
            authorized: {
                type: Boolean,
                required: true
            }
        },
        data: function () {
            return {
                selected: Object.assign({}, defaultProduct),
                shoppingMode: false
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
            activeChanged: function (product) {
                this.save(product);

                if (this.shoppingMode) {
                    this.$emit("reward", 1);
                }
            },
            confirmEdit: function () {
                this.save(this.selected);
            },
            save: function (product) {
                // do not add existing product
                if (!product["@id"]) {
                    const existing = this.products.find(p => p.category === this.selected.category &&  p.name.toLowerCase() === this.selected.name.toLowerCase());
                    if (existing) {
                        existing.active = true;
                        this.activeChanged(existing);
                        return;
                    }
                }

                const payload = {
                    "name": product.name,
                    "category": product.category,
                    "active": product.active,
                };

                if (product["@id"]) {
                    this.$emit("patch-product", product, payload);
                } else {
                    this.$emit("post-product", payload);
                }
            },
            confirmRemove: function () {
                this.$emit("delete-product", this.selected);
            },
            add: function () {
                this.selected = Object.assign({}, defaultProduct);

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
            someProductsToBuy: function() {
                return this.products.some(p => p.active);
            },
            productCategories: function () {
                const map = new Map();
                let products = this.products;
                if (this.shoppingMode) {
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
