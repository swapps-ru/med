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
    ],
];

const reorder = (list, startIndex, endIndex) => {
    const result = Array.from(list);
    const [removed] = result.splice(startIndex, 1);
    result.splice(endIndex, 0, removed);

    return result;
};

const clean = list => {
    return list.filter(arr => arr.length !== 0)
}

const getGroupIndex = (list, index) => {
    const indexes = []

    for (let i = 0; i < list.length; i++) {
        if (Array.isArray(list[i - 1])) {
            indexes.push(list[i - 1].length + indexes[i - 1] + 1)
        } else {
            indexes.push(0)
        }
    }

    return indexes.indexOf(index)
}

export default class ConstructorForm extends Component {
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
            !this.state.ctrlKeyPressed && this.setState({ ...this.state, ctrlKeyPressed: true })
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

        items = Array.from(this.state.items);

        const groupMin = data.source.droppableId === 'ConstructorForm' ? data.draggableId[data.draggableId.length - 1] : data.source.droppableId;
        const groupMan = data.destination.droppableId === 'ConstructorForm' ? data.draggableId[data.draggableId.length - 1] : data.destination.droppableId;

        let min = 0;
        let man = 0;

        for (let i = 0; i < groupMin; i++) {
            if (Array.isArray(this.state.items[i])) {
                min += this.state.items[i].length
            };
        }
        for (let i = 0; i < groupMan; i++) {
            if (Array.isArray(this.state.items[i])) {
                man += this.state.items[i].length
            };
        }

        if ((Number(data.destination.droppableId) || data.destination.droppableId === '0') && data.type === 'GROUP') {
            min = min === 0 ? 0 : min + 1;
            man = man === 0 ? 0 : man + 1;

            const item = items[data.source.droppableId][data.source.index - 1 - min];

            if (item.type === 'heading' && data.source.index - 1 - min === 0) {
                if (data.source.index - 1 - min < data.destination.index - 1 - man) {
                    const deleted = items[data.source.droppableId].splice(1, data.destination.index - 1 - man)
                    if (items[data.source.droppableId - 1]) {
                        items[data.source.droppableId - 1].push(...deleted)
                    } else {
                        items.push(deleted);
                        items = reorder(
                            items,
                            items.length - 1,
                            data.source.droppableId
                        )
                    }
                }
            } else if (data.destination.droppableId === data.source.droppableId) {
                items[data.destination.droppableId] = reorder(
                    this.state.items[data.destination.droppableId],
                    data.source.index - 1 - min,
                    data.destination.index - 1 - min,
                );
            } else {
                items[data.destination.droppableId].push(item);
                items[data.source.droppableId].splice(data.source.index - 1 - min, 1);
                items[data.destination.droppableId] = reorder(
                    items[data.destination.droppableId],
                    items[data.destination.droppableId].length - 1,
                    data.destination.index === 0 ? data.destination.index + 1 : data.destination.index - 1 - man
                );
            }
        } else {
            items = reorder(
                this.state.items,
                data.source.index - min,
                getGroupIndex(this.state.items, data.destination.index)
            );
        }

        items = clean(items);

        this.setState({
            ...this.state,
            items
        });
    };

    render() {
        return (
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
                                        let draggableIndex = i;

                                        for (let n = 0; n < i; n++) {
                                            draggableIndex += this.state.items[n].length;
                                        }

                                        return (
                                            <Draggable draggableId={`item-${draggableIndex}-${i}`} index={draggableIndex} key={`item-${draggableIndex}`}>
                                                {(provided, snapshot) => (
                                                    <div
                                                        ref={provided.innerRef}
                                                        {...provided.draggableProps}
                                                        {...provided.dragHandleProps}
                                                        style={{ ...provided.draggableProps.style }}
                                                    >
                                                        <Droppable droppableId={`${i}`} type={`GROUP`} key={`item-${i}`}>
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

                                                                        for (let n = 0; n < i; n++) {
                                                                            j += this.state.items[n].length + 1;
                                                                        }

                                                                        const draggable = snapshot.isDraggingOver || !(block && block.type === 'heading' && index === 0) || this.state.ctrlKeyPressed || (block && block.type !== 'heading' && index === 0);

                                                                        return <BlockConstructor
                                                                            {...block}
                                                                            index={j + 1}
                                                                            id={`item-${j + 1}`}
                                                                            key={`item-${j + 1}`}
                                                                            draggable={draggable}
                                                                        />
                                                                    })
                                                                    }
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
