import React from 'react'
import StyledInput from '@/Components/StyledInput'
import StyledTextArea from '@/Components/StyledTextArea'
import StyledTableList from '@/Components/StyledTableList'
import StyledSpoiler from '@/Components/StyledSpoiler'
import { Draggable, Droppable } from 'react-beautiful-dnd'

export default function BlockConstructor({ type, defaultValue, draggable, wysiwyg, index, id, marginTop }) {
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
