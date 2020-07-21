<template>
    <div>
        <div v-if="editMode" class="mb-2">
            <button class="btn btn-outline-secondary" @click="edit">
                <font-awesome-icon :icon="['fal', 'pencil']"></font-awesome-icon>
            </button>
        </div>

        <p v-if="!setting || setting.value === ''">{{ $t("embed.no_content")}}</p>

        <template v-else>
            <img v-if="isImage" :src="setting.value">
            <video v-else-if="isVideo" autoplay="autoplay" :src="setting.value"></video>
            <span v-else>{{ $t("embed.unknown_type")}}</span>
        </template>

        <b-modal id="modal-setting-edit" :centered="true" hide-header
                 @ok="confirmEdit">
            <div class="form-group">
                <input type="text" class="form-control form-control-lg" id="value"
                       :placeholder="$t('embed.value')"
                       v-model="newValue">
            </div>
        </b-modal>
    </div>
</template>

<script>
    import Spinner from "./Spinner";

    export default {
        components: {Spinner},
        props: {
            setting: {
                type: Object,
                required: false
            },
            editMode: {
                type: Boolean,
                required: true
            }
        },
        data: function () {
            return {
                newValue: null
            }
        },
        methods: {
            confirmEdit: function () {
                const payload = {
                    "value": this.newValue
                };

                if (this.setting) {
                    this.$emit("patch-setting", this.setting, payload);
                } else {
                    this.$emit("post-setting", payload);
                }
            },
            edit: function () {
                this.newValue = this.setting ? this.setting.value : "";

                this.$bvModal.show("modal-setting-edit");
            }
        },
        computed: {
            isVideo: function () {
                const videoTypes = ["webm", "mp4", "ogg"];
                return videoTypes.some(it => this.setting.value.endsWith(it))
            },
            isImage: function () {
                const imageTypes = ["png", "jpg", "gif", "webp"];
                return imageTypes.some(it => this.setting.value.endsWith(it))
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
