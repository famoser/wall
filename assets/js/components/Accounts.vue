<template>
    <div>
        <div class="btn-group btn-group-toggle">
            <label class="btn btn-outline-secondary"
                   v-for="user in users"
                   :id="user.id"
                   :class="{'active': selected === user }">
                <input type="checkbox" autocomplete="off" :true-value="user" :false-value="null" v-model="selected">
                {{user.name}}
                <span class="badge badge-pill badge-secondary">{{user.karma}}</span>
            </label>
        </div>

        <button class="btn btn-outline-secondary float-md-right" @click="reload">
            <font-awesome-icon :icon="['fal', 'sync']"></font-awesome-icon>
        </button>

        <b-modal id="modal-authentication" :centered="true" hide-header
                 @cancel="selected = null"
                 @ok="pinEntered">
            <input type="password" class="form-control form-control-lg" id="pin" placeholder="PIN" v-model="pin">
        </b-modal>
    </div>
</template>

<script>
    import Noty from 'noty';

    export default {
        props: {
            users: {
                type: Array,
                required: true
            }
        },
        data: function () {
            return {
                selected: null,
                pin: null
            }
        },
        methods: {
            pinEntered: function () {
                let randomized = Math.floor(Math.sin(this.pin) * 10000);
                if (this.selected.pin !== randomized) {
                    this.selected = null;

                    new Noty({
                        text: this.$t("messages.danger.pin_wrong"),
                        theme: 'bootstrap-v4',
                        type: 'error'
                    }).show();
                }
                this.pin = null;
                this.$emit('selected-user', this.selected);
            },
            reload: function () {
                window.location.reload();
            }
        },
        watch: {
            selected: function (value) {
                if (value !== null) {
                    this.$bvModal.show("modal-authentication");
                } else {
                    this.$emit('selected-user', null);
                }
            }
        }
    }
</script>
