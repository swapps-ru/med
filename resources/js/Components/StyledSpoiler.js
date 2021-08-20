import React from 'react';

import '@draft-js-plugins/inline-toolbar/lib/plugin.css';
import StyledInput from './StyledInput';
import StyledTextArea from './StyledTextArea';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome'

const StyledSpoiler = ({ defaultValue, type, marginTop }) => {
    return (
        <div className='table-list' style={{ marginTop, display: 'flex', flexDirection: 'row', maxWidth: '90%' }}>
            <FontAwesomeIcon className='icon' icon={type} style={{ marginRight: '10px' }} />
            <div>
                <StyledInput defaultValue={defaultValue[0]} />
                <StyledTextArea marginTop={5} defaultValue={defaultValue[1]} draggable wysiwyg />
            </div>
        </div >
    );
};

export default StyledSpoiler;
