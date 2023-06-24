<template>
    <div>
        <AddTodoComponent :getTodos="getTodos" :showError="showError" :toggleAddModal="toggleAddModal" :users="users" :addActive="addActive" :show="addShow"></AddTodoComponent>
        <EditTodoComponent :getTodos="getTodos" :showError="showError" :toggleEditModal="toggleEditModal" :users="users" :editActive="editActive" :show="editShow" :todoData="todoData"></EditTodoComponent>
        <DeleteTodoComponent :getTodos="getTodos" :showError="showError" :toggleDeleteModal="toggleDeleteModal" :deleteActive="deleteActive" :show="deleteShow" :todoData="todoData"></DeleteTodoComponent>
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-md-12 col-xl-10">
                    <div class="card mask-custom">
                        <div class="card-body p-4 text-white">
                            <div class="text-center pt-3 pb-2">
                                <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-todo-list/check1.webp"
                                    alt="Check" width="60">
                                <h2 class="my-4">Todo List</h2>
                                <a href="#!" data-toggle="tooltip" data-placement="bottom" title="Add Todo" @click="toggleAddModal" style="color:#4caf50;"><i
                                    class="bi bi-file-earmark-plus"></i></a>
                                <a href="#!" data-toggle="tooltip" data-placement="bottom" title="Refresh Todo" @click="refresh" style="color:#fbfbfb;"><i
                                    class="bi bi-arrow-clockwise"></i></a>
                            </div>
                            <TodoListComponent :users="users" :todos="todos" :showError="showError" :getTodos="getTodos" :setTodoData="setTodoData" :toggleDeleteModal="toggleDeleteModal" :toggleEditModal="toggleEditModal"></TodoListComponent>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import AddTodoComponent from './AddTodoComponent.vue';
import EditTodoComponent from './EditTodoComponent.vue';
import DeleteTodoComponent from './DeleteTodoComponent.vue';
import TodoListComponent from './TodoListComponent.vue';

export default {
    components: {
        AddTodoComponent,
        TodoListComponent,
        EditTodoComponent,
        DeleteTodoComponent
    },
    data() {
        return {
            todos: [],
            users: [],
            addActive: false,
            addShow: false,
            editActive: false,
            editShow: false,
            deleteActive: false,
            deleteShow: false,
            todoData: {
                id: null,
                title: '',
                content: '',
                priority: '',
                status: '',
                assign_user_id: null
            }
        }
    },

    created() {
        this.getTodos()
        this.getUsers()
    },

    methods: {
        getUsers(){
            fetch('/api/users')
                .then(response => {
                    if (response.ok) {
                        return response.json()
                    } else {
                        throw response
                    }
                })
                .then(response => {
                    this.users = response.data;
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
        getTodos() {
            fetch('/api/todos')
                .then(response => {
                    if (response.ok) {
                        return response.json()
                    } else {
                        throw response
                    }
                })
                .then(response => {
                    this.todos = response.data;
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
        toggleAddModal() {
            const body = document.querySelector("body");
            this.addActive = !this.addActive;
            this.addActive
                ? body.classList.add("modal-open")
                : body.classList.remove("modal-open");
            setTimeout(() => (this.addShow = !this.addShow), 10);
        },
        toggleEditModal() {
            const body = document.querySelector("body");
            this.editActive = !this.editActive;
            this.editActive
                ? body.classList.add("modal-open")
                : body.classList.remove("modal-open");
            setTimeout(() => (this.editShow = !this.editShow), 10);
        },
        toggleDeleteModal() {
            const body = document.querySelector("body");
            this.deleteActive = !this.deleteActive;
            this.deleteActive
                ? body.classList.add("modal-open")
                : body.classList.remove("modal-open");
            setTimeout(() => (this.deleteShow = !this.deleteShow), 10);
        },
        setTodoData(id, title, content, priority, status, assign_user_id) {
            this.todoData = {
                id: id,
                title: title,
                content: content,
                priority: priority,
                status: status,
                assign_user_id: assign_user_id,
            }
        },
        refresh() {
            this.getTodos()
            this.getUsers()
        },
        showError(message) {
            Vue.toasted.show(message, {
                theme: "bubble",
                position: "bottom-right",
                type: "error",
                duration: 5000
            })
        }
    }
};
</script>
