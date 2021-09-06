import React, { Component } from 'react'
import { Draggable, Droppable } from 'react-beautiful-dnd'
import ControlMenu from './Components/ControlMenu'
import { DragDropContext } from 'react-beautiful-dnd';
import BlockConstructor from '@/Components/BlockConstructor'

import './ConstructorForm.scss'

const defaultItems = [
    [
        { type: 'heading', defaultValue: 'Общие сведения', draggable: true, marginTop: 10 },
        { type: 'text-area', defaultValue: 'Nostrud dolore eiusmod dolore ea incididunt. In Lorem fugiat mollit pariatur ipsum occaecat cupidatat. Et sint aliquip occaecat ad anim ipsum exercitation in tempor mollit ipsum.', draggable: true, wysiwyg: true, marginTop: 10 },
        { type: 'list', defaultValue: ['Диабет 1ого типа', 'Диабет 2ого типа', 'Некоторые редкие типы'], draggable: true, wysiwyg: true, marginTop: 10 },
        { type: 'spoiler', defaultValue: ['Диабет 1ого типа', 'Nostrud dolore eiusmod dolore ea incididunt. In Lorem fugiat mollit pariatur ipsum occaecat cupidatat. Et sint aliquip occaecat ad anim ipsum exercitation in tempor mollit ipsum.'], draggable: true, wysiwyg: true, marginTop: 10 },
    ],
    [
        { type: 'heading', defaultValue: "Какими лекарствами лечат сахарный диабет", draggable: true, marginTop: 10 },
    ]
];

const reorder = (list, startIndex, endIndex) => {
    const result = Array.from(list);
    const [removed] = result.splice(startIndex, 1);
    result.splice(endIndex, 0, removed);

    return result;
};

export default class ConstructorForm extends Component {
    indexes = {}
    currentIndex = 0;

    constructor(props) {
        super(props);
        this.state = {
            items: defaultItems,
            ctrlKeyPressed: false
        };
        this.onDragEnd = this.onDragEnd.bind(this);
    }

    onKeydown(e) {
        if (e.key === 'c') {
            e.preventDefault()
            this.setState({ ...this.state, ctrlKeyPressed: true })
        }
    }

    onKeyup(e) {
        if (e.key === 'c') {
            e.preventDefault()
            this.setState({ ...this.state, ctrlKeyPressed: false })
        }
    }

    componentDidMount() {
        document.addEventListener('keydown', this.onKeydown.bind(this));
        document.addEventListener('keyup', this.onKeyup.bind(this));
        window.addEventListener('contextmenu', function (e) { // Не совместимо с IE младше 9 версии
            e.preventDefault();
        }, false);
    }

    componentWillUnmount() {
        document.removeEventListener('keydown', this.onKeydown);
        document.removeEventListener('keyup', this.onKeyup);
    }

    onDragEnd(data) {
        let items = [];

        if (!data.destination) {
            return;
        }

        if (Number(data.destination.droppableId) && data.type === 'GROUP') {
            items = Array.from(this.state.items);

            let min = 0;
            let man = 0;
            if (Array.isArray(items[data.source.droppableId - 2])) {
                min = items[data.source.droppableId - 2].length - 1;
            }
            if (Array.isArray(items[data.destination.droppableId - 2])) {
                man = items[data.destination.droppableId - 2].length - 1;
            }

            if (data.destination.droppableId === data.source.droppableId) {
                items[data.destination.droppableId - 1] = reorder(
                    this.state.items[data.destination.droppableId - 1],
                    data.source.index - data.destination.droppableId - 1 - min,
                    data.destination.index - data.destination.droppableId - 1 - min,
                );
            } else {
                const item = items[data.source.droppableId - 1][data.source.index - min - data.source.droppableId - 1];
                items[data.destination.droppableId - 1].push(item);
                items[data.source.droppableId - 1].splice(data.source.index - min - data.source.droppableId - 1, 1);
                items[data.destination.droppableId - 1] = reorder(
                    items[data.destination.droppableId - 1],
                    items[data.destination.droppableId - 1].length - 1,
                    data.destination.index === 0 ? data.destination.index + 1 : data.destination.index - data.destination.droppableId - 1 - man
                );
            }
        } else {
            items = reorder(
                this.state.items,
                data.source.index,
                data.destination.index
            );
        }

        this.setState({
            ...this.state,
            items
        });
    };

    getIndex() {
        this.currentIndex++;
    }

    render() {
        return (
            // TODO: Нужно завязаться на данные про порядок блоков с сервера, пока данные не готовы - хардкожу
            <DragDropContext
                onDragEnd={this.onDragEnd}
            >
                <Droppable droppableId="ConstructorForm">
                    {(provided, snapshot) => (
                        <div className="article-constructor__constructor-form"
                            ref={provided.innerRef}
                            {...provided.droppableProps}
                        >
                            {/* Верхнее меню управления */}
                            < ControlMenu />
                            {/* Компоненты конструктора */}
                            <div style={{
                                display: 'flex',
                                flexDirection: 'column',
                                alignItems: 'center',
                                maxWidth: '80%'
                            }}>
                                {this.state.items.map((item, i) => {
                                    if (Array.isArray(item)) {
                                        return (
                                            <Draggable draggableId={`item-${i}`} index={i} key={`item-${i}`}>
                                                {(provided, snapshot) => (
                                                    <div
                                                        ref={provided.innerRef}
                                                        {...provided.draggableProps}
                                                        {...provided.dragHandleProps}
                                                        style={{ ...provided.draggableProps.style }}
                                                    >
                                                        <Droppable droppableId={`${i + 1}`} type={`GROUP`} key={`item-${i + 1}`}>
                                                            {(provided, snapshot) => (
                                                                <div
                                                                    ref={provided.innerRef}
                                                                    {...provided.droppableProps}
                                                                    style={{
                                                                        display: 'flex',
                                                                        flexDirection: 'column',
                                                                        alignItems: 'center'
                                                                    }}
                                                                >
                                                                    {item.map((block, index) => {
                                                                        let j = index;
                                                                        if (Array.isArray(this.state.items[i - 1])) {
                                                                            j += this.state.items[i - 1].length
                                                                        };

                                                                        return <BlockConstructor
                                                                            {...block}
                                                                            index={j + 2}
                                                                            id={`item-${j + 2}`}
                                                                            key={`item-${j + 2}`}
                                                                            draggable={(block && block.type !== 'heading' && index !== 0) || this.state.ctrlKeyPressed}
                                                                        />
                                                                    })}
                                                                    {provided.placeholder}
                                                                </div >
                                                            )}
                                                        </Droppable>
                                                    </div>
                                                )}
                                            </Draggable>
                                        );
                                    }

                                    return <BlockConstructor
                                        {...item}
                                        index={i}
                                        id={`item-${i}`}
                                        key={`item-${i}`}
                                    />
                                })}
                            </div>
                            {provided.placeholder}
                        </div >
                    )}
                </Droppable>
            </DragDropContext>
        )
    }
}
