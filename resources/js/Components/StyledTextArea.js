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
import { Draggable } from 'react-beautiful-dnd';

const StyledTextArea = ({ defaultValue, type, marginTop, wysiwyg, id, index, draggable }) => {
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

    const html = <div
        className='editor'
        onClick={focus}
        style={{ marginTop }}
    >
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
    </div>;

    const textarea = type ? <div style={{ display: 'flex', flexDirection: 'row', alignItems: 'center' }}>
        {<FontAwesomeIcon className='icon' icon={type} style={{ marginRight: '10px' }} />}
        {html}
    </div> : html;

    return draggable ?
        <Draggable draggableId={id} index={index}>
            {(provided, snapshot) => (
                <div
                    ref={provided.innerRef}
                    {...provided.draggableProps}
                    {...provided.dragHandleProps}
                    style={{ ...provided.draggableProps.style, width: '700px', display: 'flex', justifyContent: 'center' }}
                >
                    {textarea}
                </div>
            )}
        </Draggable>
        : textarea;
    ;
};

export default StyledTextArea;
