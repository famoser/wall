<template>
    <div>
        <div v-if="authorizedUser !== null" class="mb-2">
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

        <template v-for="answeredQuestion in answeredQuestions">
            <p class="lead mb-0">{{ answeredQuestion.text }}</p>
            <span v-if="mode === 'edit'">
                <button class="btn btn-sm" @click="edit(question)">
                    <font-awesome-icon
                            class="text-warning"
                            :icon="['fal', 'pencil']">
                    </font-awesome-icon>
                </button>
                <button class="btn btn-sm" @click="remove(question)">
                    <font-awesome-icon
                            class="text-danger"
                            :icon="['fal', 'trash']">
                    </font-awesome-icon>
                </button>
            </span>
        </template>

        <b-modal id="modal-question-edit" :centered="true" hide-header
                 @ok="confirmEdit">
            <div class="form-group">
                <input type="text" class="form-control form-control-lg" id="text"
                       :placeholder="$t('entity.question.text')"
                       v-model="selected.text">
            </div>
            <div class="form-group">
                <select class="form-control form-control-lg" v-model="selected.repetition">
                    <option :value="0">{{ $t("questions.repetitions.none") }}</option>
                    <option :value="1">{{ $t("questions.repetitions.daily") }}</option>
                </select>
            </div>
        </b-modal>

        <b-modal id="modal-question-remove" :centered="true" hide-header
                 @ok="confirmRemove">
            {{ $t("messages.danger.confirm_remove") }}
        </b-modal>
    </div>
</template>

<script>
    import Spinner from "./Spinner";

    import moment from 'moment'

    const defaultQuestion = {
        "text": "",
        "repetition": 1
    }

    export default {
        components: {Spinner},
        props: {
            questions: {
                type: Array,
                required: true
            },
            answers: {
                type: Array,
                required: true
            },
            authorizedUser: {
                type: Object,
                required: false
            }
        },
        data: function () {
            return {
                selected: Object.assign({}, defaultQuestion),
                mode: 'view'
            }
        },
        methods: {
            save: function (question) {
                const payload = {
                    "text": question.text,
                    "repetition": question.repetition,
                }

                if (question["@id"]) {
                    this.$emit("patch-question", question, payload)
                } else {
                    this.$emit("post-question", payload);
                }
            },
            confirmEdit: function () {
                this.save(this.selected);
            },
            confirmRemove: function () {
                this.$emit("delete-question", this.selected);
            },
            add: function () {
                this.selected = Object.assign({}, defaultQuestion)

                this.$bvModal.show("modal-question-edit");
            },
            addAnswer: function (question, value) {
                const existingAnswer = this.ownAnswerForQuestion(question);

                const payload = {
                    "value": value,
                    "givenAt": moment().toISOString(),
                    "question": question['@id'],
                    "user": question.repetition,
                }
                if (existingAnswer) {
                    this.$emit("patch-answer", existingAnswer, payload)
                } else {
                    this.$emit("post-answer", payload);
                }
            },
            removeAnswer: function () {
                const existingAnswer = this.ownAnswerForQuestion(question);
                this.$emit("delete-answer", existingAnswer);
            },
            edit: function (question) {
                this.selected = question

                this.$bvModal.show("modal-question-edit");
            },
            remove: function (question) {
                this.selected = question

                this.$bvModal.show("modal-question-remove");
            },
            answersForQuestion: function (question, availableAnswers) {
                let answers = availableAnswers.filter(a => a.question['@id'] === question['@id']);
                if (question.repetition === 1) { // daily
                    const today = moment().startOf("day").toISOString();
                    answers = answers.filter(a => a.givenAt > today);
                }

                return answers;
            },
            ownAnswerForQuestion: function (question) {
                return this.answersForQuestion(question, this.answers)
                    .find(a => a.user['@id'] === this.authorizedUser['@id']);
            }
        },
        computed: {
            openQuestions: function () {
                if (!this.authorizedUser) {
                    return [];
                }

                return this.questions.filter(question => {
                    return !this.ownAnswerForQuestion(question);
                });
            },
            answeredQuestions: function () {
                this.questions.map(question => {
                    const answers = this.answersForQuestion(question, this.answers);

                    return {
                        "text": question.text,
                        "answers": answers
                    }
                });
            }
        }
    }
</script>
