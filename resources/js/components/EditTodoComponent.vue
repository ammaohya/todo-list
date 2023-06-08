<template>
    <div>
        <div ref="editModal" class="modal fade" :class="{ show, 'd-block': editActive }" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Todo</h5>
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
                                <label for="status-select">Status</label>
                                <select class="form-control" id="status-select" v-model="status">
                                    <option value="New">New</option>
                                    <option value="Ongoing">Ongoing</option>
                                    <option value="Pending">Pending</option>
                                    <option value="Finished">Finished</option>
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
        <div v-if="editActive" class="modal-backdrop fade show"></div>
    </div>
</template>

<script>
export default {
    props: {
        editActive: Boolean,
        show: Boolean,
        toggleEditModal: Function,
        todoData: Object,
        getTodos: Function
    },
    data() {
        return {
            id: this.todoData.id,
            title: this.todoData.title,
            content: this.todoData.content,
            priority: this.todoData.priority,
            status: this.todoData.status,
            isButtonDisabled: false
        };
    },

    mounted() {
        console.log('Edit Component mounted.')
    },

    watch: {
        todoData(newData) {
            this.id = newData.id
            this.title = newData.title
            this.content = newData.content
            this.priority = newData.priority
            this.status = newData.status
            this.checkFormValidity()
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
                "status": this.status
            }
            fetch(`/api/todo/${ this.id }`, {
                method: 'PUT',
                headers: headers,
                body: JSON.stringify(body)
            })
                .then(response => {
                    if (response.ok) {
                        this.chileToggleModal()
                        this.getTodos()
                    }
                })
                .catch(err => {
                    console.log(err)
                });
        },
        chileToggleModal() {
            this.toggleEditModal()
        }
    }
}
</script>