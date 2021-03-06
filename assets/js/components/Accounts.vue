<template>
    <div>
        <p class="alert alert-success" v-if="this.users.length === 0">
            {{ $t("accounts.welcome") }}!<br/>
            {{ $t("accounts.wall_description") }}<br/>
            {{ $t("accounts.create_account") }}
        </p>

        <div v-if="!editMode" class="btn-group btn-group-toggle">
            <label class="btn btn-outline-secondary"
                   v-for="user in orderedUsers"
                   :id="user['@id']"
                   :class="{'active': authenticated === user }">
                <input type="checkbox" autocomplete="off" :true-value="user" :false-value="null"
                       @change="authenticateUser(user)">
                {{user.name}}
                <span class="badge badge-pill"
                      :class="{'badge-light': selected === user, 'badge-secondary': selected !== user}">
                    {{user.karma}}
                </span>
            </label>
        </div>

        <div v-if="editMode" class="d-inline mr-2">
            <div class="btn-group">
                <button class="btn" @click="edit">
                    <font-awesome-icon class="text-warning" :icon="['fal', 'pencil']"></font-awesome-icon>
                </button>
                <button class="btn" @click="remove">
                    <font-awesome-icon class="text-danger" :icon="['fal', 'trash']"></font-awesome-icon>
                </button>
            </div>
            {{authenticated.name}}
        </div>

        <div v-if="authorized" class="btn-group-toggle d-inline">
            <label class="btn btn-outline-secondary"
                   :class="{'active': editMode }">
                <input type="checkbox" autocomplete="off" @change="$emit('toggle-edit-mode')">
                <font-awesome-icon :icon="['fal', 'pencil']"></font-awesome-icon>
            </label>
        </div>

        <button v-if="editMode || this.users.length === 0" class="btn btn-outline-secondary" @click="add">
            <font-awesome-icon :icon="['fal', 'plus']"></font-awesome-icon>
        </button>

        <button v-if="!editMode" class="btn btn-outline-secondary float-md-right" @click="reload">
            <font-awesome-icon :icon="['fal', 'sync']"></font-awesome-icon>
        </button>

        <b-modal id="modal-authentication" v-model="modalAuthenticationShow" :centered="true" hide-header no-close-on-backdropgi
                 @cancel="authenticated = null"
                 @ok="pinEntered">
            <div class="form-group">
                <input ref="password" type="password" class="form-control form-control-lg" id="pin"
                       :placeholder="$t('entity.user.pin')" @keydown.enter="pinEntered" autofocus v-model="pin">
            </div>
        </b-modal>

        <b-modal id="modal-user-edit" :centered="true" hide-header no-close-on-backdrop
                 @ok="confirmEdit">
            <div class="form-group">
                <input type="text" class="form-control form-control-lg" id="name"
                       :placeholder="$t('entity.user.name')"
                       v-model="selected.name">
            </div>
            <div class="form-group">
                <input type="password" class="form-control form-control-lg" id="pin-edit"
                       :placeholder="$t('entity.user.pin')"
                       v-model="newPin">
            </div>
        </b-modal>

        <b-modal id="modal-user-remove" :centered="true" hide-header no-close-on-backdrop
                 @ok="confirmRemove">
            {{ $t("messages.danger.confirm_remove") }}
        </b-modal>
    </div>
</template>

<script>
    import Noty from 'noty';

    const defaultUser = {
        "name": "",
        "pin": ""
    }

    export default {
        props: {
            users: {
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
                authenticated: null,
                selected: Object.assign({}, defaultUser),
                pin: null,
                modalAuthenticationShow: false,
                newPin: null
            }
        },
        methods: {
            reload: function () {
                window.location.reload();
            },
            logout: function () {
                localStorage.setItem('accounts.pin', null);
                this.authenticated = null;
                this.$emit('select-user', null, null);
            },
            login: function (user, pin) {
                localStorage.setItem('accounts.pin', pin);
                this.authenticated = user;
                this.$emit('select-user', this.authenticated, pin);
            },
            pseudoRandomizePIN: function (pin) { // segurity
                return Math.floor(Math.sin(pin) * 10000);
            },
            pinEntered: function () {
                this.modalAuthenticationShow = false;
                let randomized = this.pseudoRandomizePIN(this.pin)
                if (this.selected.pin !== randomized) {
                    new Noty({
                        text: this.$t("messages.danger.pin_wrong"),
                        theme: 'bootstrap-v4',
                        type: 'error'
                    }).show();
                } else {
                    this.login(this.selected, this.pin);
                }
                this.pin = null;
            },
            confirmEdit: function () {
                const update = {
                    "name": this.selected.name
                }

                if (!this.newPin && !this.selected["@id"]) {
                    new Noty({
                        text: this.$t("messages.danger.must_set_pin"),
                        theme: 'bootstrap-v4',
                        type: 'error'
                    }).show();
                    return;
                }

                if (this.newPin) {
                    update.pin = this.pseudoRandomizePIN(this.newPin);
                    this.newPin = null;
                }

                if (this.selected["@id"]) {
                    this.$emit("patch-user", this.selected, update)
                } else {
                    this.$emit("post-user", update);
                }
            },
            confirmRemove: function () {
                this.$emit("delete-user", this.authenticated);
                this.logout();
                this.$emit('toggle-edit-mode');
            },
            add: function () {
                this.selected = Object.assign({}, defaultUser);
                this.$bvModal.show("modal-user-edit");
            },
            edit: function () {
                this.selected = this.authenticated;
                this.$bvModal.show("modal-user-edit");
            },
            remove: function () {
                this.selected = this.authenticated;
                this.$bvModal.show("modal-user-remove");
            },
            authenticateUser: function (user) {
                if (this.authenticated !== user) {
                    this.selected = user;
                    this.$bvModal.show("modal-authentication");
                } else {
                    this.logout();
                }
            }
        },
        watch: {
            modalAuthenticationShow: function (value) {
                if (value) {
                    window.setTimeout(() => {
                        this.$refs.password.focus();
                    }, 100);
                }
            }
        },
        computed: {
            authorized: function () {
                return this.authenticated;
            },
            orderedUsers: function () {
                return this.users.sort((u1, u2) => u1.name.localeCompare(u2.name));
            }
        },
        mounted() {
            // attempt login
            const pin = parseInt(localStorage.getItem('accounts.pin'));
            this.authenticated = this.users.find(u => u.pin === this.pseudoRandomizePIN(pin))
            if (!this.authenticated) {
                localStorage.setItem('accounts.pin', null);
            } else {
                this.$emit('select-user', this.authenticated);
            }
        }
    }
</script>
