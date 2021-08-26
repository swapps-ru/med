import React from 'react';

import '@draft-js-plugins/inline-toolbar/lib/plugin.css';
import StyledInput from './StyledInput';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome'
import { Draggable } from 'react-beautiful-dnd';

const StyledTableList = ({ defaultValue, type, marginTop, draggable, id, index }) => {
    const tableList = <div
        className='table-list'
        style={{ marginTop, display: 'flex', flexDirection: 'row', maxWidth: '100%' }}
    >
        <FontAwesomeIcon className='icon' icon={type} style={{ marginRight: '10px' }} />
        <div style={{ minWidth: '400px' }}>
            <StyledInput wysiwyg defaultValue={defaultValue[0]} />
            <StyledInput wysiwyg defaultValue={defaultValue[1]} />
            <StyledInput wysiwyg defaultValue={defaultValue[2]} />
        </div>
    </div >;

    return draggable ? <Draggable draggableId={id} index={index}>
        {(provided, snapshot) => (
            <div
                ref={provided.innerRef}
                {...provided.draggableProps}
                {...provided.dragHandleProps}
            >
                {tableList}
            </div>
        )}
    </Draggable>
        : tableList;
};

export default StyledTableList;
