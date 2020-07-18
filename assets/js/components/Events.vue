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
        </div>

        <template v-for="event in orderedEvents">
            <p class="mt-2">
                {{ formatDateTime(event.startAt) }} - {{event.name}}

                <span v-if="mode === 'edit'">
                    <button class="btn btn-sm" @click="edit(event)">
                        <font-awesome-icon
                                class="text-warning"
                                :icon="['fal', 'pencil']">
                        </font-awesome-icon>
                    </button>
                    <button class="btn btn-sm" @click="remove(event)">
                        <font-awesome-icon
                                class="text-danger"
                                :icon="['fal', 'trash']">
                        </font-awesome-icon>
                    </button>
                </span>
            </p>
        </template>

        <b-modal id="modal-event-edit" :centered="true" hide-header
                 @ok="confirmEdit">
            <div class="form-group">
                <input type="text" class="form-control form-control-lg" id="name"
                       :placeholder="$t('entity.event.name')"
                       v-model="selected.name">
            </div>
            <div class="form-group">
                <flat-pickr
                        v-model="selected.startAt"
                        :config="datePickerConfig"
                        class="form-control form-control-lg"
                        :placeholder="$t('entity.event.startAt')"
                        name="date">
                </flat-pickr>
            </div>
        </b-modal>

        <b-modal id="modal-event-remove" :centered="true" hide-header
                 @ok="confirmRemove">
            {{ $t("messages.danger.confirm_remove") }}
        </b-modal>
    </div>
</template>

<script>
    import Spinner from "./Spinner";
    import moment from "moment"
    import flatPickr from 'vue-flatpickr-component';

    const defaultEvent = {
        "name": "",
        "startAt": null
    }

    export default {
        components: {Spinner, flatPickr},
        props: {
            events: {
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
                selected: Object.assign({}, defaultEvent),
                mode: 'view'
            }
        },
        methods: {
            formatDateTime: function (date) {
                return moment(date).format("DD.MM.YYYY hh:mm");
            },
            confirmEdit: function () {
                const payload = {
                    "name": this.selected.name,
                    "startAt": this.selected.startAt,
                };

                if (this.selected["@id"]) {
                    this.$emit("patch-event", this.selected, payload);
                } else {
                    this.$emit("post-event", payload);
                }
            },
            confirmRemove: function () {
                this.$emit("delete-event", this.selected);
            },
            add: function () {
                this.selected = Object.assign({}, defaultEvent)

                this.$bvModal.show("modal-event-edit");
            },
            edit: function (event) {
                this.selected = event

                this.$bvModal.show("modal-event-edit");
            },
            remove: function (event) {
                this.selected = event

                this.$bvModal.show("modal-event-remove");
            }
        },
        computed: {
            orderedEvents: function () {
                return this.events.sort((e1, e2) => e1.startAt.localeCompare(e2.startAt));
            },
            datePickerConfig: function () {
                return {
                    altInput: true,
                    altFormat: "d.m.Y H:i",
                    dateFormat: "Y-m-dTH:i",
                    parseDate: (datestr, format) => {
                        return moment(datestr, format, true).toISOString();
                    },
                    enableTime: true,
                    time_24hr: true
                }
            }
        }
    }
</script>
