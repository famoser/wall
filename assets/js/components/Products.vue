<template>
    <div>
        <button class="btn btn-outline-secondary" @click="add">
            <font-awesome-icon :icon="['fal', 'plus']"></font-awesome-icon>
        </button>
        <template v-for="(productCategory, index) in productCategories">
            <p class="lead mb-0">{{ $t("products.categories." + productCategory.name) }}</p>
            <div v-for="product in productCategory.products" class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input"
                       v-model="product.active"
                       @change="saveProduct(product)"
                       :id="product.id">
                <label class="custom-control-label" :for="product.id">{{product.name}}</label>
            </div>
            <div class="mb-4" v-if="index < productCategories.length - 1"></div>
        </template>

        <b-modal id="modal-product-edit" :centered="true" hide-header
                 v-if="selected !== null"
                 @cancel="selected = null"
                 @ok="confirmEdit">
            <input type="text" class="form-control form-control-lg" id="name" placeholder="name" v-model="selected.name">
            <select class="form-control form-control-lg" v-model="selected.category">
                <option v-for="index in 7" :value="index">{{ $t("products.categories." + categoryToTranslationId(index)) }}</option>
            </select>
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
            }
        },
        data: function () {
            return {
                selected: defaultProduct
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
            saveProduct: function (product) {
                const update = {
                    "name": product.name,
                    "category": product.category,
                    "active": product.active,
                }
                if (product.persistedInDatabase) {
                    this.$emit("patch-product", update)
                } else {
                    this.$emit("add-product", update);
                }
            },
            confirmEdit: function () {
                this.saveProduct(this.selected);
                this.selected = null;
            },
            add: function () {
                this.selected = defaultProduct

                this.$bvModal.show("modal-product-edit");
            }
        },
        computed: {
            productCategories: function () {
                const map = new Map();
                this.products.forEach((product) => {
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
