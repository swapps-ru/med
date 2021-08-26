import React from 'react';

import '@draft-js-plugins/inline-toolbar/lib/plugin.css';
import StyledInput from './StyledInput';
import StyledTextArea from './StyledTextArea';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome'
import { Draggable } from 'react-beautiful-dnd';

const StyledSpoiler = ({ defaultValue, type, marginTop, draggable, id, index }) => {
    const spoiler = <div className='table-list' style={{ marginTop, display: 'flex', flexDirection: 'row' }}>
        <FontAwesomeIcon className='icon' icon={type} style={{ marginRight: '10px' }} />
        <div>
            <StyledInput defaultValue={defaultValue[0]} />
            <StyledTextArea marginTop={5} defaultValue={defaultValue[1]} wysiwyg />
        </div>
    </div >;

    return draggable ? <Draggable draggableId={id} index={index}>
        {(provided, snapshot) => (
            <div
                ref={provided.innerRef}
                {...provided.draggableProps}
                {...provided.dragHandleProps}
            >
                {spoiler}
            </div>
        )}
    </Draggable>
        : spoiler;;
};

export default StyledSpoiler;
