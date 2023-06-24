<template>
    <div>
        <div ref="deleteModal" class="modal fade" :class="{ show, 'd-block': deleteActive }" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Delete Todo</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                            @click="chileToggleModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure to delete this todo?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="chileToggleModal">No</button>
                        <button type="button" class="btn btn-primary" @click="handleSubmit">Yes</button>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="deleteActive" class="modal-backdrop fade show"></div>
    </div>
</template>

<script>
export default {
    props: {
        deleteActive: Boolean,
        show: Boolean,
        toggleDeleteModal: Function,
        todoData: Object,
        getTodos: Function,
        showError: Function,
    },
    data() {
        return {
            id: null
        }
    },

    watch: {
        todoData(newData) {
            this.id = newData.id
        }
    },

    methods: {
        handleSubmit() {
            // Hide the modal manually
            this.$nextTick(() => {
                this.$bvModal.hide('modal-prevent-closing')
            })
            fetch(`/api/todo/${this.id}`, {
                method: 'DELETE',
            })
                .then(response => {
                    if (response.ok) {
                        this.chileToggleModal()
                        this.getTodos()
                    } else {
                        throw response
                    }
                })
                .catch(error => {
                    if (typeof error.json === "function") {
                        error.json().then(jsonError => {
                            console.log(jsonError);
                            this.showError(jsonError.message);
                        }).catch(genericError => {
                            this.showError("Something got wrong");
                        });
                    } else {
                        console.log(error);
                        this.showError("Something got wrong");
                    }
                });
        },
        chileToggleModal() {
            this.toggleDeleteModal()
        }
    }
}
</script>