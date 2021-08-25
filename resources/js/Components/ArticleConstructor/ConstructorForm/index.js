import React, { useCallback, useEffect, useState } from 'react'
import { Droppable } from 'react-beautiful-dnd';
import ControlMenu from './Components/ControlMenu'
import { DragDropContext } from 'react-beautiful-dnd';
import BlockConstructor from '@/Components/BlockConstructor'

import './ConstructorForm.scss'

const defaultItems = [
    { type: 'heading', defaultValue: 'Общие сведения', draggable: true },
    { type: 'text-area', defaultValue: 'Nostrud dolore eiusmod dolore ea incididunt. In Lorem fugiat mollit pariatur ipsum occaecat cupidatat. Et sint aliquip occaecat ad anim ipsum exercitation in tempor mollit ipsum.', draggable: true, wysiwyg: true },
    { type: 'list', defaultValue: ['Диабет 1ого типа', 'Диабет 2ого типа', 'Некоторые редкие типы'], draggable: true, wysiwyg: true },
    { type: 'spoiler', defaultValue: ['Диабет 1ого типа', 'Nostrud dolore eiusmod dolore ea incididunt. In Lorem fugiat mollit pariatur ipsum occaecat cupidatat. Et sint aliquip occaecat ad anim ipsum exercitation in tempor mollit ipsum.'], draggable: true, wysiwyg: true },
    { type: 'heading', defaultValue: "Какими лекарствами лечат сахарный диабет", draggable: true },
];

const reorder = (list, startIndex, endIndex) => {
    const result = Array.from(list);
    const [removed] = result.splice(startIndex, 1);
    result.splice(endIndex, 0, removed);

    return result;
};

export default function ConstructorForm() {
    const [items, setItems] = useState(defaultItems);

    useEffect(() => {
        console.log(items);
    }, [items])

    const onDragEnd = useCallback((data) => {
        if (!data.destination) {
            return;
        }

        const result = reorder(
            items,
            data.source.index,
            data.destination.index
        );

        setItems(result);
    }, []);

    return (
        // TODO: Нужно завязаться на данные про порядок блоков с сервера, пока данные не готовы - хардкожу
        <DragDropContext
            onDragEnd={onDragEnd}
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

                        {items.map((item, i) => (
                            <BlockConstructor {...item} index={i} id={`item-${i}`} key={`item-${i}`} />
                        ))}
                        {provided.placeholder}
                    </div >
                )}
            </Droppable>
        </DragDropContext>
    )
}
