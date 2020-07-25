<template>
    <div>
        <div v-if="editMode" class="mb-2 mt-2">
            <button class="btn btn-outline-secondary" @click="add">
                <font-awesome-icon :icon="['fal', 'plus']"></font-awesome-icon>
            </button>
        </div>

        <masonry
                :cols="{default: 3, 1800: 4, 992: 1}"
                :gutter="{default: '10px'}"
                class="mt-2">
            <div class="grid-item" v-for="embed in embeds">
                <div class="card">
                    <div class="card-body">
                        <div v-if="editMode" class="button-float">
                            <button class="btn btn-outline-secondary" @click="remove(embed)">
                                <font-awesome-icon :icon="['fal', 'trash']"></font-awesome-icon>
                            </button>
                        </div>

                        <img v-if="embed.type === 1" class="img-fluid" :src="embed.content">
                        <video v-else-if="embed.type === 2" :src="embed.content"></video>
                        <p v-else-if="embed.type === 3" class="blockquote">
                            {{embed.content}}
                        </p>
                        <iframe v-else-if="embed.type === 4" class="youtube"
                                :src="embed.content"
                                frameborder="0"
                                allowfullscreen>
                        </iframe>
                    </div>
                </div>
            </div>
        </masonry>

        <b-modal id="modal-embed-edit" :centered="true" hide-header
                 @ok="confirmEdit">
            <div class="form-group">
                <input type="text" class="form-control form-control-lg" id="content"
                       :placeholder="$t('entity.embed.content')"
                       v-model="selected.content">
            </div>
            <div class="form-group">
                <select class="form-control form-control-lg" v-model="selected.type">
                    <option v-for="index in 4" :value="index">
                        {{ $t("embeds.types." + typeToTranslationId(index)) }}
                    </option>
                </select>
            </div>
        </b-modal>

        <b-modal id="modal-embed-remove" :centered="true" hide-header
                 @ok="confirmRemove">
            {{ $t("messages.danger.confirm_remove") }}
        </b-modal>

    </div>
</template>

<style>
    .button-float {
        position: absolute;
        right: 5px;
        top: 0;
    }

    .grid-item {
        margin-bottom: 10px;
    }
</style>

<script>
    import Spinner from "./Spinner";

    let defaultEmbed = {
        content: "",
        type: 1
    }

    export default {
        components: {Spinner},
        props: {
            embeds: {
                type: Array,
                required: true
            },
            editMode: {
                type: Boolean,
                required: true
            }
        },
        data: function () {
            return {
                selected: defaultEmbed
            }
        },
        methods: {
            typeToTranslationId: function (index) {
                switch (index) {
                    case 1:
                        return "image";
                    case 2:
                        return "video";
                    case 3:
                        return "quote"
                    case 4:
                        return "youtube"
                }
            },
            confirmEdit: function () {
                const payload = {
                    "content": this.selected.content,
                    "type": this.selected.type,
                };

                if (this.selected["@id"]) {
                    this.$emit("patch-embed", this.selected, payload);
                } else {
                    this.$emit("post-embed", payload);
                    this.$emit("reward", 1);
                }
            },
            confirmRemove: function () {
                this.$emit("delete-embed", this.selected);
            },
            add: function () {
                this.selected = Object.assign({}, defaultEmbed)

                this.$bvModal.show("modal-embed-edit");
            },
            remove: function (embed) {
                this.selected = embed

                this.$bvModal.show("modal-embed-remove");
            }
        }
    }
</script>

<style lang="scss">
    $i: 0;
    @while $i <= 100 {
        .progress-#{$i} {
            width: $i#{'%'};
        }

        $i: $i+1
    }

</style>
