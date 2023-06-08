<template>
    <div>
        <AddTodoComponent :getTodos="getTodos" :toggleAddModal="toggleAddModal" :addActive="addActive" :show="addShow"></AddTodoComponent>
        <EditTodoComponent :getTodos="getTodos" :toggleEditModal="toggleEditModal" :editActive="editActive" :show="editShow" :todoData="todoData"></EditTodoComponent>
        <DeleteTodoComponent :getTodos="getTodos" :toggleDeleteModal="toggleDeleteModal" :deleteActive="deleteActive" :show="deleteShow" :todoData="todoData"></DeleteTodoComponent>
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
                                <a href="#!" data-toggle="tooltip" data-placement="bottom" title="Refresh Todo" @click="getTodos" style="color:#fbfbfb;"><i
                                    class="bi bi-arrow-clockwise"></i></a>
                            </div>
                            <TodoListComponent :todos="todos" :setTodoData="setTodoData" :toggleDeleteModal="toggleDeleteModal" :toggleEditModal="toggleEditModal"></TodoListComponent>
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
                status: ''
            }
        }
    },

    created() {
        this.getTodos()
    },

    methods: {
        getTodos() {
            fetch('/api/todos')
                .then(response => response.json())
                .then(response => {
                    this.todos = response.data;
                })
                .catch(err => console.log(err));
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
        setTodoData(id, title, content, priority, status) {
            this.todoData = {
                id: id,
                title: title,
                content: content,
                priority: priority,
                status: status,
            }
        }
    }
};
</script>
