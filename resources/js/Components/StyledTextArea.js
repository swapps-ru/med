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

const StyledTextArea = ({ defaultValue, type, marginTop, wysiwyg }) => {
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
        editorKey="StyledTextArea"
        editorState={editorState}
        onChange={onChange}
        plugins={plugins}
        ref={(element) => {
        editor.current = element;
    }}
        />
        {wysiwyg && <InlineToolbar />}
    </div>
    );
};

export default StyledTextArea;