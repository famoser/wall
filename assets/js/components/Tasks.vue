<template>
    <div>
        <div v-if="editMode" class="mb-2">
            <button class="btn btn-outline-secondary" @click="add">
                <font-awesome-icon :icon="['fal', 'plus']"></font-awesome-icon>
            </button>
        </div>

        <template v-if="!editMode" v-for="task in orderedTasks">
            <div class="progress mt-2">
                <div ref="progress"
                     class="progress-bar" role="progressbar"
                     :class="'progress-' + Math.floor(Math.min(task.overduePercentage, 100))">
                </div>
            </div>

            <p class="mb-3">
                {{ task.task.name }}

                <button v-if="authorized" class="btn btn-sm float-right" @click="done(task.task)">
                    <font-awesome-icon
                            class="text-success"
                            :icon="['fal', 'check']">
                    </font-awesome-icon>
                </button>
            </p>
        </template>

        <template v-if="editMode" v-for="task in orderedTasks">
            <p class="mb-1">
                {{ task.task.name }} -
                ({{ task.task.intervalInDays}} / {{task.task.intervalInDays}})
                <span>
                    <button class="btn btn-sm" @click="edit(task.task)">
                        <font-awesome-icon
                                class="text-warning"
                                :icon="['fal', 'pencil']">
                        </font-awesome-icon>
                    </button>
                    <button class="btn btn-sm" @click="remove(task.task)">
                        <font-awesome-icon
                                class="text-danger"
                                :icon="['fal', 'trash']">
                        </font-awesome-icon>
                    </button>
                </span>
            </p>
        </template>

        <b-modal id="modal-task-edit" :centered="true" hide-header
                 @ok="confirmEdit">
            <div class="form-group">
                <input type="text" class="form-control form-control-lg" id="name"
                       :placeholder="$t('entity.task.name')"
                       v-model="selected.name">
            </div>
            <div class="form-group row">
                <div class="col-md-4">
                    <label for="interval-in-days" class="col-form-label-lg">
                        {{ $t('entity.task.interval_in_days') }}
                    </label>
                </div>
                <div class="col-md-8">
                    <input type="number" class="form-control form-control-lg" id="interval-in-days"
                           :placeholder="$t('entity.task.interval_in_days')"
                           v-model="selected.intervalInDays">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-4">
                    <label for="reward" class="col-form-label-lg">{{ $t('entity.task.reward') }}</label>
                </div>
                <div class="col-md-8">
                    <input type="number" class="form-control form-control-lg" id="reward"
                           :placeholder="$t('entity.task.reward')"
                           v-model="selected.reward">
                </div>
            </div>
        </b-modal>

        <b-modal id="modal-task-remove" :centered="true" hide-header
                 @ok="confirmRemove">
            {{ $t("messages.danger.confirm_remove") }}
        </b-modal>
    </div>
</template>

<script>
    import Spinner from "./Spinner";
    import moment from "moment"

    const defaultTask = {
        "name": "",
        "intervalInDays": 7,
        "reward": 1
    }

    export default {
        components: {Spinner},
        props: {
            tasks: {
                type: Array,
                required: true
            },
            authorized: {
                type: Boolean,
                required: true
            },
            editMode: {
                type: Boolean,
                required: true
            }
        },
        data: function () {
            return {
                selected: Object.assign({}, defaultTask)
            }
        },
        methods: {
            formatDateTime: function (date) {
                return moment(date).format("DD.MM.YYYY HH:mm");
            },
            confirmEdit: function () {
                const payload = {
                    "name": this.selected.name,
                    "intervalInDays": parseInt(this.selected.intervalInDays),
                    "reward": parseInt(this.selected.reward)
                };

                if (this.selected["@id"]) {
                    this.$emit("patch-task", this.selected, payload);
                } else {
                    this.$emit("post-task", payload);
                }
            },
            done: function (task) {
                const payload = {
                    "lastExecutionAt": moment().format()
                };

                this.$emit("patch-task", task, payload);

                this.$emit("reward", task.reward);
            },
            confirmRemove: function () {
                this.$emit("delete-task", this.selected);
            },
            add: function () {
                this.selected = Object.assign({}, defaultTask)

                this.$bvModal.show("modal-task-edit");
            },
            edit: function (task) {
                this.selected = task

                this.$bvModal.show("modal-task-edit");
            },
            remove: function (task) {
                this.selected = task

                this.$bvModal.show("modal-task-remove");
            }
        },
        computed: {
            orderedTasks: function () {
                // show most pressing task at the top
                return this.tasks.map(task => {
                    if (!task.lastExecutionAt) {
                        return {
                            overduePercentage: 100,
                            task
                        }
                    }

                    const diff = moment().diff(moment(task.lastExecutionAt));
                    const diffInHours = moment.duration(diff).asMinutes();
                    const relativeDiff = diffInHours / (task.intervalInDays * 24 * 60);

                    return {
                        overduePercentage: relativeDiff * 100,
                        task
                    }
                })
                    .sort((e1, e2) => e2.overduePercentage - e1.overduePercentage);
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
