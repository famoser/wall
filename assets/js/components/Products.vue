<template>
    <div>
        <template v-for="(productCategory, index) in productCategories">
            <p class="lead mb-0">{{ $t("products.categories." + productCategory.name) }}</p>
            <div v-for="product in productCategory.products" class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" :id="product.id">
                <label class="custom-control-label" :for="product.id">{{product.name}}</label>
            </div>
            <div class="mb-4" v-if="index < productCategories.length - 1"></div>
        </template>
    </div>
</template>

<script>
    import Spinner from "./Spinner";
    export default {
        components: {Spinner},
        props: {
            activeUser: {
                type: Object
            },
            products: {
                type: Array,
                required: true
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
