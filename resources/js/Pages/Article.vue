<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Lista de Artigos
            </h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-4 py-4">
                    <div class="bg-teal-100 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md my-3"
                         role="alert" v-if="$page.props.message">
                        <div class="flex">
                            <div>
                                <p class="text-sm">{{ $page.props.message }}</p>
                            </div>
                        </div>
                    </div>
                    <button @click="openModal()"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded my-3">Criar Um
                        Novo Post
                    </button>
                    <table class="table-fixed w-full">
                        <thead>
                        <tr class="bg-gray-100">
                            <th class="px-4 py-2 w-20">#</th>
                            <th class="px-4 py-2">Título</th>
                            <th class="px-4 py-2">Conteúdo</th>
                            <th class="px-4 py-2">Acção</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="row in articles.data" :key="row.id">
                            <td class="border px-4 py-2">{{ row.id }}</td>
                            <td class="border px-4 py-2">{{ row.title }}</td>
                            <td class="border px-4 py-2">{{ row.body }}</td>
                            <td class="border px-4 py-2">
                                <button @click="edit(row)"
                                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    Editar
                                </button>
                                <button @click="deleteRow(row)"
                                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                    Excluír
                                </button>
                            </td>
                        </tr>
                        </tbody>
                    </table>

                    <pagination
                            :current="current"
                            :total="Object.keys(this.articles).length"
                            :per-page="perPage"
                            @page-changed="loadArticles"

                            text-before-input="Ir para página"
                            text-after-input="ir"/>
                    <!--<tailable-pagination-->
                    <!--:data="articles"-->
                    <!--:showNumber="true">-->

                    <!--</tailable-pagination>-->

                    <div class="fixed z-10 inset-0 overflow-y-auto ease-out duration-400" v-if="isOpen">
                        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">

                            <div class="fixed inset-0 transition-opacity">
                                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                            </div>

                            <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>​
                            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
                                 role="dialog" aria-modal="true" aria-labelledby="modal-headline">
                                <form>
                                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                        <div class="">
                                            <div class="mb-4">
                                                <label for="exampleFormControlInput1"
                                                       class="block text-gray-700 text-sm font-bold mb-2">Título:</label>
                                                <input type="text"
                                                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                                       id="exampleFormControlInput1" placeholder="Insira o título"
                                                       v-model="form.title">
                                                <div v-if="$page.props.errors.title" class="text-red-500">{{
                                                    $page.props.errors.title[0] }}
                                                </div>
                                            </div>

                                            <div class="mb-4">
                                                <label for="exampleFormControlInput2"
                                                       class="block text-gray-700 text-sm font-bold mb-2">Conteúdo:</label>
                                                <textarea
                                                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                                        id="exampleFormControlInput2" placeholder="Insira o conteúdo"
                                                        v-model="form.body"></textarea>
                                                <div v-if="$page.props.errors.body" class="text-red-500">{{
                                                    $page.props.errors.body[0] }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                              <button wire:click.prevent="store()" type="button"
                                      class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-green-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-green-500 focus:outline-none focus:border-green-700 focus:shadow-outline-green transition ease-in-out duration-150 sm:text-sm sm:leading-5"
                                      v-show="!editMode" @click="save(form)">
                                Salvar
                              </button>
                            </span>
                                        <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                              <button wire:click.prevent="store()" type="button"
                                      class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-green-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-green-500 focus:outline-none focus:border-green-700 focus:shadow-outline-green transition ease-in-out duration-150 sm:text-sm sm:leading-5"
                                      v-show="editMode" @click="update(form)">
                                Actualizar
                              </button>
                            </span>
                                        <span class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:w-auto">

                              <button @click="closeModal()" type="button"
                                      class="inline-flex justify-center w-full rounded-md border border-gray-300 px-4 py-2 bg-white text-base leading-6 font-medium text-gray-700 shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                                Cancel
                              </button>
                            </span>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </app-layout>
</template>

<script>
    import AppLayout from '@/Layouts/AppLayout'
    import Welcome from '@/Jetstream/Welcome'
    import Pagination from '@/Layouts/Pagination'
    //import Paginationn from 'tailable-pagination'

    export default {
        components: {
            AppLayout,
            Welcome,
            Pagination,
        },
        props: {
            errors: Array,
            articles: Object,
        },
        data() {
            return {
                current: 1,
                perPage: 5,
                // total: 20,
                editMode: false,
                isOpen: false,
                form: {
                    title: null,
                    body: null,
                },
            }
        },

        created() {
            //this.loadArticles();

            console.log("total de artigos :" + Object.keys(this.articles).length);
            console.log("total de artigos :" + this.loadArticles);
            // this.$inertia.get('/article').then(response => {
            //     this.articles = response;
            // });

        },

        methods: {
            openModal() {
                this.isOpen = true;
            },
            closeModal() {
                this.isOpen = false;
                this.reset();
                this.editMode = false;
            },
            reset() {
                this.form = {
                    title: null,
                    body: null,
                }
            },
            save(articles) {

                this.$inertia.post('/article', articles)
                    .then(() => {

                        Toast.fire({
                            icon: 'success',
                            title: 'Artigo Criado com sucesso!'
                        });

                    });
                this.reset();
                this.closeModal();
                this.editMode = false;
            },
            edit(articles) {
                this.form = Object.assign({}, articles);
                this.editMode = true;
                this.openModal();
            },
            update(articles) {
                articles._method = 'PATCH';
                this.$inertia.post('/article/edit/' + articles.id, articles)
                    .then(() => {

                        Toast.fire({
                            icon: 'success',
                            title: 'Artigo actualizado com sucesso!'
                        });

                    });
                this.reset();
                this.closeModal();
            },
            deleteRow(articles) {
                Swal.fire({
                    title: 'Tem a certeza?',
                    text: "Não será capaz de reverter isso",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    cancelButtonText: 'Cancelar',
                    confirmButtonText: 'Sim, exclua-o!'
                }).then((result) => {

                    if (result.value) {
                        articles._method = 'DELETE';
                        this.$inertia.post('/article/delete/' + articles.id, articles)
                            .then(() => {

                                Swal.fire(
                                    'Eliminado!',
                                    'Artigo excluído com sucessso',
                                    'success'
                                );
                            }).catch(() => {

                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Algo correu mal!',
                                footer: '<a href="#">Porquê que tenho erro?</a>'
                            });
                        });
                    }
                });

                this.reset();
                this.closeModal();
            },

            loadArticles(page) {
                //our get results from a laravel endpoint
                this.$inertia.get("/article?page=" + page)
                    .then(data => (this.articles = data));

                //pick data from controller and push it into users object

            },
        },
    }
</script>