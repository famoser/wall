<template>
    <div>
        <div v-if="editMode" class="mb-2 mt-2">
            <button class="btn btn-outline-secondary" @click="add">
                <font-awesome-icon :icon="['fal', 'plus']"></font-awesome-icon>
            </button>
        </div>

        <masonry
                :cols="{default: 2, 1800: 4, 992: 1}"
                :gutter="{default: '10px'}"
                class="mt-2">
            <div class="grid-item" v-for="embed in orderedEmbeds">
                <div v-if="editMode" class="button-float-wrapper">
                    <button class="btn btn-outline-danger" @click="remove(embed)">
                        <font-awesome-icon :icon="['fal', 'trash']"></font-awesome-icon>
                    </button>
                </div>

                <img v-if="embed.type === 1"
                     :src="embed.content"
                     class="image">
                <video v-else-if="embed.type === 2"
                       :src="embed.content"
                       class="video" controls>
                </video>
                <blockquote v-else-if="embed.type === 3" class="blockquote p-2 bg-white border mb-0">
                    <p class="mb-0">{{embed.content}}</p>
                    <footer class="blockquote-footer">{{$t('embeds.blockquote.author')}}</footer>
                </blockquote>
                <div v-else-if="embed.type === 4"
                     class="youtube-wrapper">
                    <iframe
                            class="youtube"
                            :src="toEmbedUrl(embed.content)"
                            allowfullscreen>
                    </iframe>
                </div>
            </div>
        </masonry>

        <b-button v-if="orderedEmbeds.length < embeds.length" @click="maxEntries += 10">
          {{$t('embeds.show_more')}}
        </b-button>

        <b-modal id="modal-embed-edit" :centered="true" hide-header no-close-on-backdrop
                 @ok="confirmEdit">maxEntries
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

        <b-modal id="modal-embed-remove" :centered="true" hide-header no-close-on-backdrop
                 @ok="confirmRemove">
            {{ $t("messages.danger.confirm_remove") }}
        </b-modal>
    </div>
</template>

<style scoped>
    .button-float-wrapper {
        position: absolute;
        z-index: 1000;
        right: 5px;
        top: 5px;
        background: white;
        opacity: 0.92;
    }

    .image, .video {
        width: 100%;
        height: auto;
    }

    .youtube-wrapper {
        position: relative;
        width: 100%;
        padding-top: 56.25%; /* 16:9 Aspect Ratio */
    }

    .youtube {
        position: absolute;
        top: 0;
        right: 0;

        width: 100%;
        height: 100%;
    }

    .grid-item {
        margin-bottom: 10px;
        position: relative;
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
                selected: defaultEmbed,
                maxEntries: 10
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
            toEmbedUrl: function (youtubeUrl) {
                try {
                  // get video id; like https://www.youtube.com/watch?v=OxGn-9qAi7Q&list=PLba_FJwIiiR_Mn3cAWwaI4JeBGLD6I2Us&index=5&t=0s
                  let url = new URL(youtubeUrl)
                  let viewId = url.searchParams.get('v')

                  return "https://www.youtube.com/embed/" + viewId
                } catch (e) {
                  console.log(e)
                }

                return youtubeUrl
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
        },
        computed: {
            orderedEmbeds: function() {
                return [...this.embeds].reverse().slice(0, this.maxEntries);
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
