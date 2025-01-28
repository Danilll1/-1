@extends('layouts.layout')

@section('content')

<div class="list-container">
    <div class="panel bg-light p-3">
        <div class="controls d-flex justify-content-between align-items-center mt-3">

            <a href="{{ route('home') }}" class="d-flex align-items-center justify-content-left text-decoration-none"
                style="text-align: center; padding: 10px; border-radius: 5px; background-color: #f8f9fa; color: #212529;">
                <div class="logo me-2">
                    <i class="fa-duotone fa-solid fa-clipboard-list"
                        style="--fa-primary-color: #dbdbdb; --fa-secondary-color: #dbdbdb; font-size: 2rem;"></i>
                </div>
                <h5 class="mb-0">Задачи</h5>
            </a>

            <style>
                a {
                    text-decoration: none;
                    /* Убирает подчеркивание */
                    color: #212529;
                    /* Цвет текста (темно-серый) */
                }

                a:hover {
                    color: #212529;
                    /* Цвет текста при наведении (темно-серый) */
                    background-color: #e2e6ea;
                    /* Цвет фона при наведении (можно изменить) */
                }
            </style>

            <div class="dropdown">

                <span class="dropdownLayerBtn" id="statusDropdown" role="button" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    Статус <i class="fa-solid fa-chevron-down ms-2"></i>
                </span>
                <ul class="dropdown-menu" aria-labelledby="statusDropdown">
                    <li><a class="dropdown-item" href="{{ route('home') }}"><i class="fa-solid fa-chevron-right"
                                style="color: #000000;"></i> Все</a></li>
                    <li><a class="dropdown-item btnActive" href="{{ route('home', ['status' => 'Активно']) }}"><i
                                class="fa-solid fa-chevron-right" style="color: #000000;"></i> Активно</a></li>
                    <li><a class="dropdown-item btnCompleted" href="{{ route('home', ['status' => 'Завершено']) }}"><i
                                class="fa-solid fa-chevron-right" style="color: #000000;"></i> Завершено</a></li>
                    <li><a class="dropdown-item btnQueue" href="{{ route('home', ['status' => 'Ожидание']) }}"><i
                                class="fa-solid fa-chevron-right" style="color: #000000;"></i> Ожидание</a></li>
                </ul>

                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    Сортировать по <i class="fa-solid fa-arrow-up-wide-short ms-2"></i>
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <li>
                        <div class="form-check">
                            <input type="checkbox" id="date-checkbox" class="form-check-input" />
                            <label for="date-checkbox" class="form-check-label"> Дате</label>
                            <input type="date" id="date-input" class="form-control mt-1"
                                onchange="updateDate(this.value)" />
                        </div>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('home', ['sort' => 'asc']) }}">
                            По возрастанию <i class="fa-solid fa-chevron-right ms-2"></i>
                        </a>
                        <a class="dropdown-item" href="{{ route('home', ['sort' => 'desc']) }}">
                            По убыванию <i class="fa-solid fa-chevron-right ms-2"></i>
                        </a>
                    </li>
                </ul>

                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    Создать задачу <i class="fa-solid fa-pen-to-square ms-2"></i>
                </button>

            </div>
        </div>
    </div>
    @if (isset($tasks))
        @foreach ($tasks as $task)
            <div class="window p-3" style="border: 3px solid #f8f8fa;">
                <div class="context mb-3">
                    <div class="title h5">
                        {{ $task->name }}
                    </div>
                    <div class="status mt-1">
                        <span class="
                                                                        @if ($task->status == 'Активно') sSuccess 
                                                                        @elseif ($task->status == 'Завершено') sDanger 
                                                                        @elseif ($task->status == 'Ожидание') sDepleted 
                                                                        @endif">
                            {{ $task->status }}
                        </span>
                    </div>
                    <div class="deadline mt-1 text-muted">
                        До {{ $task->deadline }}
                    </div>
                </div>
                <div class="divider mb-3"></div>
                <div class="description mb-3">
                    {{ $task->description }}
                </div>
                <div class="button-group">
                    <button type="button" class="btn btn-outline-primary me-2" data-bs-toggle="modal"
                        data-bs-target="#exampleModal{{ $task->id }}">
                        <i class="fa-solid fa-pen"></i> Редактировать
                    </button>
                    <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal"
                        data-bs-target="#deleteModal{{ $task->id }}">
                        <i class="fa-solid fa-trash"></i> Удалить
                    </button>
                </div>
            </div>


            <!-- Modal -->
            <style>
                .inputEdit {
                    margin-bottom: 15px;
                    /* Задайте нужный размер отступа */
                }
            </style>

            <form action="{{ route('edit', $task->id) }}" method="POST">
                @csrf
                <div class="modal fade" id="exampleModal{{ $task->id }}" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content editModal">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Редактирование задачи:<br>{{ $task->name }}
                                </h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <input value="{{ $task->name }}" type="text" class="form-control inputEdit" name="name"
                                    placeholder="Название">
                                <textarea class="form-control inputEdit descriptionEdit" name="description"
                                    placeholder="Описание">{{ $task->description }}</textarea>
                                <select class="form-select inputEdit" name="status" aria-label="Default select example">
                                    <option value="" disabled>Выберите статус</option>
                                    <option value="Активно" {{ $task->status === 'Активно' ? 'selected' : '' }}>Активно</option>
                                    <option value="Завершено" {{ $task->status === 'Завершено' ? 'selected' : '' }}>Завершено
                                    </option>
                                    <option value="Ожидание" {{ $task->status === 'Ожидание' ? 'selected' : '' }}>Ожидание
                                    </option>
                                </select>
                                <input value="{{ $task->deadline }}" type="date" class="form-control inputEdit" name="deadline"
                                    placeholder="Дедлайн">
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-outline-primary me-2">Сохранить</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>


            <!-- Modal для удаления -->
            <div class="modal fade" id="deleteModal{{ $task->id }}" tabindex="-1" aria-labelledby="deleteModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content editModal">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteModalLabel">Подтвердите удаление</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Вы уверены, что хотите удалить задачу: <strong>{{ $task->name }}</strong>?
                        </div>
                        <div class="modal-footer">
                            <form action="{{ route('delete', $task->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger">Удалить</button>
                                <button type="button" class="btn btn-outline-primary me-2"
                                    data-bs-dismiss="modal">Отмена</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <h1>Нет заданий</h1>
    @endif

    <!-- Modal для задачи-->
    <form action="{{ route('create') }}" method="POST">
        @csrf
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog ">
                <div class="modal-content editModal">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Создание задачи</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="text" class="form-control inputEdit mb-3" name="name" placeholder="Название">
                        <textarea class="form-control inputEdit mb-3 descriptionEdit" name="description"
                            placeholder="Описание"></textarea>
                        <select class="form-select inputEdit mb-3" name="status" aria-label="Default select example">
                            <option value="Активно">Активно</option>
                            <option value="Завершено">Завершено</option>
                            <option value="Ожидание">Ожидание</option>
                        </select>
                        <input type="date" class="form-control inputEdit mb-3" name="deadline" placeholder="Дедлайн">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-outline-primary me-2">Сохранить</button>
                    </div>
                </div>
            </div>
        </div>
    </form>



</div>