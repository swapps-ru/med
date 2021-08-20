import React from 'react';

import '@draft-js-plugins/inline-toolbar/lib/plugin.css';
import StyledInput from './StyledInput';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome'

const StyledTableList = ({ defaultValue, type, marginTop }) => {
    return (
        <div className='table-list' style={{ marginTop, display: 'flex', flexDirection: 'row' }}>
            <FontAwesomeIcon className='icon' icon={type} style={{ marginRight: '10px' }} />
            <div style={{ minWidth: '400px' }}>
                <StyledInput wysiwyg defaultValue={defaultValue[0]} />
                <StyledInput wysiwyg defaultValue={defaultValue[1]} />
                <StyledInput wysiwyg defaultValue={defaultValue[2]} />
            </div>
        </div >
    );
};

export default StyledTableList;
