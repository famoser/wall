<template>
    <div>
        <div class="btn-group btn-group-toggle">
            <label class="btn btn-outline-secondary"
                   v-for="user in users"
                   :id="user.id"
                   :class="{'active': selected === user }">
                <input type="checkbox" autocomplete="off" :true-value="user" :false-value="null" v-model="selected">
                {{user.name}}
                <span class="badge badge-pill"
                      :class="{'badge-light': selected === user, 'badge-secondary': selected !== user}">
                    {{user.karma}}
                </span>
            </label>
        </div>

        <button class="btn btn-outline-secondary float-md-right" @click="reload">
            <font-awesome-icon :icon="['fal', 'sync']"></font-awesome-icon>
        </button>

        <b-modal id="modal-authentication" v-model="modelAuthenticationShow" :centered="true" hide-header
                 @cancel="selected = null"
                 @ok="pinEntered">
            <div class="form-group">
                <input ref="password" type="password" class="form-control form-control-lg" id="pin"
                       :placeholder="$t('entity.user.pin')" autofocus v-model="pin">
            </div>
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
                selected: this.users.find(u => u.pin === parseInt(localStorage.getItem('accounts.pin'))),
                pin: null,
                modelAuthenticationShow: false
            }
        },
        methods: {
            pinEntered: function () {
                let randomized = Math.floor(Math.sin(this.pin) * 10000); // segurity
                if (this.selected.pin !== randomized) {
                    this.selected = null;

                    new Noty({
                        text: this.$t("messages.danger.pin_wrong"),
                        theme: 'bootstrap-v4',
                        type: 'error'
                    }).show();
                } else {
                    localStorage.setItem('accounts.pin', randomized.toString());
                }
                this.pin = null;
                this.$emit('select-user', this.selected);
            },
            reload: function () {
                window.location.reload();
            }
        },
        watch: {
            modelAuthenticationShow: function (value) {
                if (value) {
                    window.setTimeout(() => {
                        this.$refs.password.focus();
                    }, 100);
                }
            },
            selected: function (value) {
                if (value !== null) {
                    this.$bvModal.show("modal-authentication");
                } else {
                    this.$emit('select-user', null);
                    localStorage.setItem('accounts.pin', null);
                }
            }
        },
        mounted() {
            if (this.selected === null) {
                localStorage.setItem('accounts.pin', null);
            } else {
                this.$emit('select-user', this.selected);
            }
        }
    }
</script>
