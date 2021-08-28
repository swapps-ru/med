import React from 'react'
import StyledInput from '@/Components/StyledInput'
import StyledTextArea from '@/Components/StyledTextArea'
import StyledTableList from '@/Components/StyledTableList'
import StyledSpoiler from '@/Components/StyledSpoiler'
import { Draggable, Droppable } from 'react-beautiful-dnd'

export default function BlockConstructor({ type, defaultValue, draggable, wysiwyg, index, id, isGroup, marginTop }) {
    if (isGroup) {
        const items = arguments[0];

        return (
            <Draggable draggableId={id} index={index}>
                {(provided, snapshot) => (
                    <div
                        ref={provided.innerRef}
                        {...provided.draggableProps}
                        {...provided.dragHandleProps}
                        style={{ ...provided.draggableProps.style }}
                    >
                        <Droppable droppableId={`${index + 1}`} type={`GROUP`}>
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
                                    {/* {Object.keys(items).map((key, i) => ( */}
                                    <BlockConstructor
                                        {...items[0]}
                                        index={index + 2}
                                        id={`item-${index + 2}`}
                                        key={`item-${index + 2}`}
                                        draggable={false}
                                    />
                                    {items[1] && <BlockConstructor
                                        {...items[1]}
                                        index={index + 3}
                                        id={`item-${index + 3}`}
                                        key={`item-${index + 3}`}
                                    />}
                                    {items[2] && <BlockConstructor
                                        {...items[2]}
                                        index={index + 4}
                                        id={`item-${index + 4}`}
                                        key={`item-${index + 4}`}
                                    />}
                                    {items[3] && <BlockConstructor
                                        {...items[3]}
                                        index={index + 5}
                                        id={`item-${index + 5}`}
                                        key={`item-${index + 5}`}
                                    />}
                                    {/* ))} */}
                                    {provided.placeholder}
                                </div >
                            )}
                        </Droppable>
                    </div>
                )}
            </Draggable>
        );
    }

    switch (type) {
        case 'heading':
            return <StyledInput
                wysiwyg={wysiwyg}
                id={id}
                index={index}
                type='heading'
                defaultValue={defaultValue}
                draggable={draggable}
                marginTop={marginTop}
            />
        case 'text-area':
            return <StyledTextArea
                wysiwyg={wysiwyg}
                id={id}
                index={index}
                type='align-right'
                defaultValue={defaultValue}
                draggable={draggable}
                marginTop={marginTop}
            />
        case 'list':
            return <StyledTableList
                wysiwyg={wysiwyg}
                id={id}
                index={index}
                type='list'
                defaultValue={defaultValue}
                draggable={draggable}
                marginTop={marginTop}
            />
        case 'spoiler':
            return <StyledSpoiler
                wysiwyg={wysiwyg}
                id={id}
                index={index}
                type='caret-down'
                defaultValue={defaultValue}
                draggable={draggable}
                marginTop={marginTop}
            />
        default:
            return;
    }
}
