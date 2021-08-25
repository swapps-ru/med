import React from 'react'
import StyledInput from '@/Components/StyledInput'
import StyledTextArea from '@/Components/StyledTextArea'
import StyledTableList from '@/Components/StyledTableList'
import StyledSpoiler from '@/Components/StyledSpoiler'

export default function BlockConstructor({ type, defaultValue, draggable, wysiwyg, index, id }) {
    switch (type) {
        case 'heading':
            return <StyledInput
                wysiwyg={wysiwyg}
                id={id}
                index={index}
                type='heading'
                defaultValue={defaultValue}
                draggable={draggable}
            />
        case 'text-area':
            return <StyledTextArea
                wysiwyg={wysiwyg}
                id={id}
                index={index}
                type='align-right'
                defaultValue={defaultValue}
                draggable={draggable}
            />
        case 'list':
            return <StyledTableList
                wysiwyg={wysiwyg}
                id={id}
                index={index}
                type='list'
                defaultValue={defaultValue}
                draggable={draggable}
            />
        case 'spoiler':
            return <StyledSpoiler
                wysiwyg={wysiwyg}
                id={id}
                index={index}
                type='caret-down'
                defaultValue={defaultValue}
                draggable={draggable}
            />
        default:
            return;
    }
}
