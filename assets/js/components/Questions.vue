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

        <template v-if="mode !== 'edit'" v-for="questionWithAnswer in questionsWithAnswers">
            <p class="mb-0 mt-2">
                <span class="lead">{{ questionWithAnswer.question.text }}</span>
                <span v-if="questionWithAnswer.yes.length > 0" class="text-success ml-2">{{ questionWithAnswer.yes.join(", ") }}</span>
                <span v-if="questionWithAnswer.no.length > 0" class="text-danger ml-2">{{ questionWithAnswer.no.join(", ") }}</span>

                <template v-if="authorizedUser !== null">
                    <span v-if="questionWithAnswer.own">
                        <button class="btn btn-sm" @click="removeAnswer(questionWithAnswer.own)">
                            <font-awesome-icon
                                    class="text-secondary"
                                    :icon="['fal', 'times']">
                            </font-awesome-icon>
                        </button>
                    </span>
                        <span v-else class="ml-2 mr-2">
                        <button class="btn btn-outline-success" @click="addAnswer(questionWithAnswer.question, 1)">
                            {{ $t("questions.answers.yes") }}
                        </button>
                        <button class="btn btn-outline-danger" @click="addAnswer(questionWithAnswer.question, 2)">
                            {{ $t("questions.answers.no") }}
                        </button>
                    </span>
                </template>
            </p>
        </template>

        <template v-if="mode === 'edit'" v-for="question in questions">
            <p class="lead mb-0">
                {{ question.text }}
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
            </p>
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
            users: {
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
                    "user": this.authorizedUser["@id"],
                }
                if (existingAnswer) {
                    this.$emit("patch-answer", existingAnswer, payload)
                } else {
                    this.$emit("post-answer", payload);
                }
            },
            removeAnswer: function (answer) {
                this.$emit("delete-answer", answer);
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
                let answers = availableAnswers.filter(a => a.question === question['@id']);
                if (question.repetition === 1) { // daily
                    const today = moment().startOf("day").toISOString();
                    answers = answers.filter(a => a.givenAt > today);
                }

                return answers;
            },
            ownAnswerForQuestion: function (question) {
                return this.ownAnswer(this.answersForQuestion(question, this.answers));
            },
            ownAnswer: function (availableAnswers) {
                if (!this.authorizedUser) {
                    return null;
                }

                return availableAnswers.find(a => a.user === this.authorizedUser["@id"]);
            }
        },
        computed: {
            questionsWithAnswers: function () {
                let userNameById = {};
                this.users.forEach(u => userNameById[u["@id"]] = u.name);

                return this.questions.map(question => {
                    const answers = this.answersForQuestion(question, this.answers);
                    const own = this.ownAnswer(answers);

                    const yes = answers.filter(a => a.value === 1).map(a => userNameById[a.user]);
                    const no = answers.filter(a => a.value === 2).map(a => userNameById[a.user]);

                    return {
                        question,
                        own,
                        yes,
                        no
                    }
                });
            }
        }
    }
</script>
