<template>
    <table class="table text-white mb-0">
        <thead>
            <tr>
                <th scope="col">Todo</th>
                <th scope="col">Priority</th>
                <th scope="col">Status</th>
                <th scope="col">Last Update Time</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="todo in todoList" class="fw-normal">
                <th>
                    <div class="d-flex w-100 justify-content-between">
                        <h5 class="mb-1">{{ todo.title }}</h5>
                    </div>
                    <p class="mb-1">{{ todo.content }}</p>
                </th>
                <td class="align-middle">
                    <h6 class="mb-0"><span v-bind:class="getPriorityClass(todo.priority)">{{ todo.priority }} priority</span></h6>
                </td>
                <td class="align-middle">
                    <span>{{ todo.status }}</span>
                </td>
                <td class="align-middle">
                    <span>{{ todo.updated_at }}</span>
                </td>
                <td class="align-middle">
                    <a href="#!" data-toggle="tooltip" data-placement="bottom" title="Edit" @click="chileToggleEditModal(todo)"><i
                            class="bi bi-pencil-square"></i></a>
                    <a href="#!" data-toggle="tooltip" data-placement="bottom" title="Remove" @click="chileToggleDeleteModal(todo)" style="color:#ff9800;"><i
                            class="bi bi-trash-fill"></i></a>
                </td>
            </tr>
        </tbody>
    </table>
</template>

<script>
export default {
    props: {
        setTodoData: Function,
        toggleEditModal: Function,
        toggleDeleteModal: Function,
        todos: Array
    },

    data() {
        return {
            todoList: this.todos,
            addActive: false,
            addShow: false,
            editActive: false,
            editShow: false,
            todoData: {
                id: null,
                title: '',
                content: '',
                priority: '',
                status: ''
            }
        }
    },

    watch: {
        todos(newData) {
            this.todoList = newData
        }
    },

    methods: {
        getPriorityClass(todoStatus) {
            switch (todoStatus) {
                case "High":
                    return {
                        "badge bg-danger": true
                    }
                case "Middle":
                    return {
                        "badge bg-warning": true
                    } 
                case "Low":
                    return {
                        "badge bg-success": true
                    }
            }
        },
        chileToggleEditModal(todo) {
            this.setTodoData(todo.id, todo.title, todo.content, todo.priority, todo.status)
            this.toggleEditModal()
        },
        chileToggleDeleteModal(todo) {
            this.setTodoData(todo.id, todo.title, todo.content, todo.priority, todo.status)
            this.toggleDeleteModal()
        }
    }
};
</script>
