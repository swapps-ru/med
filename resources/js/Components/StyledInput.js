import React, {
    ReactElement,
    useEffect,
    useMemo,
    useRef,
    useState,
} from 'react';
import { EditorState } from 'draft-js';
import Editor, { createEditorStateWithText } from '@draft-js-plugins/editor';
import createInlineToolbarPlugin from '@draft-js-plugins/inline-toolbar';
import editorStyles from './editorStyles.scss';
import '@draft-js-plugins/inline-toolbar/lib/plugin.css';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome'

import { getDefaultKeyBinding } from 'draft-js';

// Отменяем перенос строки на Enter
function myKeyBindingFn(e) {
    if (e.keyCode === 13 /* `Enter` key */) {
        return null;
    }
    return getDefaultKeyBinding(e);
}

const StyledInput = ({ defaultValue, type, wysiwyg, marginTop }) => {
    const [plugins, InlineToolbar] = useMemo(() => {
        const inlineToolbarPlugin = createInlineToolbarPlugin();
        return [wysiwyg ? [inlineToolbarPlugin] : [], inlineToolbarPlugin.InlineToolbar];
    }, []);

    const [editorState, setEditorState] = useState(() =>
        createEditorStateWithText('')
    );

    useEffect(() => {
        setEditorState(createEditorStateWithText(defaultValue || ''));
    }, []);

    const editor = useRef(null);

    const onChange = (value) => {
        setEditorState(value);
    };

    const focus = () => {
        editor.current && editor.current.focus();
    };

    return (
        <div className='editor' onClick={focus} style={{ marginTop }}>
            {type && <FontAwesomeIcon className='icon' icon={type} />}
            <Editor
                editorKey="StyledInput"
                editorState={editorState}
                onChange={onChange}
                plugins={plugins}
                ref={(element) => {
                    editor.current = element;
                }}
                keyBindingFn={myKeyBindingFn}
            />
            {wysiwyg && <InlineToolbar />}
        </div>
    );
};

export default StyledInput;
