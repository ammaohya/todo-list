<template>
    <div>
        <div ref="modal" class="modal fade" :class="{ show, 'd-block': addActive }" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">New Todo</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                            @click="chileToggleModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form @change="checkFormValidity">
                            <div class="form-group">
                                <label for="new-todo-title" class="col-form-label">Title:</label>
                                <b-form-input id="new-todo-title" v-model="title" class="form-control"
                                    :state="title.length >= 1" aria-describedby="new-todo-title-feedback"
                                    placeholder="Enter todo title" trim></b-form-input>
                                <b-form-invalid-feedback id="new-todo-title-feedback">
                                    Enter at least 1 letters
                                </b-form-invalid-feedback>
                            </div>
                            <div class="form-group">
                                <label for="content-text" class="col-form-label">Content:</label>
                                <b-form-textarea id="content-text" class="form-control" v-model="content"
                                    :state="content.length >= 10" placeholder="Enter at least 10 characters"
                                    rows="3"></b-form-textarea>
                            </div>
                            <div class="form-group">
                                <label for="priority-select">Priority</label>
                                <select class="form-control" id="priority-select" v-model="priority">
                                    <option value="Low">Low</option>
                                    <option value="Middle">Middle</option>
                                    <option value="High">High</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="assign-select">Assign User</label>
                                <select class="form-control" id="assign-select" v-model="assign">
                                    <option selected :value=null>Nobody</option>
                                    <option v-for="user in users" :value="user.id">{{user.name}}</option>
                                </select>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="chileToggleModal">Cancel</button>
                        <button type="button" class="btn btn-primary" @click="handleSubmit" :disabled="isButtonDisabled">Save</button>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="addActive" class="modal-backdrop fade show"></div>
    </div>
</template>

<script>
export default {
    props: {
        addActive: Boolean,
        show: Boolean,
        toggleAddModal: Function,
        getTodos: Function,
        users: Array,
    },
    data() {
        return {
            title: '',
            content: '',
            priority: 'Low',
            isButtonDisabled: true,
            assign: null
        }
    },

    methods: {
        checkFormValidity() {
            const valid = this.title.length >= 1 && this.content.length >= 10 && this.priority.length >= 1
            if (valid) {
                this.isButtonDisabled = false
            } else {
                this.isButtonDisabled = true
            }
            return valid
        },
        resetModal() {
            this.title = ''
            this.content = ''
            this.priority = 'Low'
            this.assign = null
        },
        handleSubmit() {
            // Hide the modal manually
            this.$nextTick(() => {
                this.$bvModal.hide('modal-prevent-closing')
            })
            const headers = {
                "Content-Type": "application/json",
            }
            const body = {
                "title": this.title,
                "content": this.content,
                "priority": this.priority,
                "assign_user_id": this.assign
            }
            fetch('/api/todo', {
                method: 'POST',
                headers: headers,
                body: JSON.stringify(body)
            })
                .then(response => {
                    if (response.ok) {
                        this.resetModal()
                        this.chileToggleModal()
                        this.getTodos()
                    }
                })
                .catch(err => {
                    console.log(err)

                });
        },
        chileToggleModal() {
            this.toggleAddModal()
        }
    }
}
</script>