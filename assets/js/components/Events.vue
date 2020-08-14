<template>
    <div>
        <p v-if="events.length === 0">{{ $t("events.no_events")}}</p>

        <div v-if="editMode" class="mb-2">
            <button class="btn btn-outline-secondary" @click="add">
                <font-awesome-icon :icon="['fal', 'plus']"></font-awesome-icon>
            </button>
        </div>

        <template v-if="!editMode" v-for="event in orderedEvents">
            <p class="mb-3">
                {{ formatDateTime(event.startAt) }} - {{event.name}} <br/>
                <span class="text-secondary">{{ formatFromNow(event.startAt) }}</span>
            </p>
        </template>

        <template v-if="editMode" v-for="event in orderedEvents">
            <p class="mb-1">
                {{ formatDateTime(event.startAt) }} - {{event.name}}

                <span>
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
            editMode: {
                type: Boolean,
                required: true
            }
        },
        data: function () {
            return {
                selected: Object.assign({}, defaultEvent)
            }
        },
        methods: {
            formatDateTime: function (date) {
                return moment(date).format("DD.MM.YYYY HH:mm");
            },
            formatFromNow: function (data) {
                return moment(data).fromNow()
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
                    altFormat: "DD.MM.YYYY HH:mm",
                    dateFormat: "iso",
                    parseDate: (datestr, format) => {
                        if (format === "iso") {
                            return moment(datestr).toDate();
                        } else {
                            return moment(datestr, format, true).toDate();
                        }
                    },
                    formatDate: (date, format, locale) => {
                        if (format === "iso") {
                            return moment(date).format();
                        } else {
                            return moment(date).format(format);
                        }
                        // locale can also be used
                    },
                    enableTime: true,
                    time_24hr: true
                }
            }
        }
    }
</script>
