import React, { Component } from 'react';

class WysiwygEditor extends Component {
    constructor(props) {
        super(props);
        const toolbar = mergeRecursive(defaultToolbar, props.toolbar);
        const wrapperId = props.wrapperId
            ? props.wrapperId
            : Math.floor(Math.random() * 10000);
        this.wrapperId = `rdw-wrapper-${wrapperId}`;
        this.modalHandler = new ModalHandler();
        this.focusHandler = new FocusHandler();
        this.blockRendererFn = getBlockRenderFunc(
            {
                isReadOnly: this.isReadOnly,
                isImageAlignmentEnabled: this.isImageAlignmentEnabled,
                getEditorState: this.getEditorState,
                onChange: this.onChange,
            },
            props.customBlockRenderFunc
        );
        this.editorProps = this.filterEditorProps(props);
        this.customStyleMap = this.getStyleMap(props);
        this.compositeDecorator = this.getCompositeDecorator(toolbar);
        const editorState = this.createEditorState(this.compositeDecorator);
        extractInlineStyle(editorState);
        this.state = {
            editorState,
            editorFocused: false,
            toolbar,
        };
    }

    componentDidMount() {
        this.modalHandler.init(this.wrapperId);
    }

    componentDidUpdate(prevProps) {
        if (prevProps === this.props) return;
        const newState = {};
        const { editorState, contentState } = this.props;
        if (!this.state.toolbar) {
            const toolbar = mergeRecursive(defaultToolbar, toolbar);
            newState.toolbar = toolbar;
        }
        if (
            hasProperty(this.props, 'editorState') &&
            editorState !== prevProps.editorState
        ) {
            if (editorState) {
                newState.editorState = EditorState.set(editorState, {
                    decorator: this.compositeDecorator,
                });
            } else {
                newState.editorState = EditorState.createEmpty(this.compositeDecorator);
            }
        } else if (
            hasProperty(this.props, 'contentState') &&
            contentState !== prevProps.contentState
        ) {
            if (contentState) {
                const newEditorState = this.changeEditorState(contentState);
                if (newEditorState) {
                    newState.editorState = newEditorState;
                }
            } else {
                newState.editorState = EditorState.createEmpty(this.compositeDecorator);
            }
        }
        if (
            prevProps.editorState !== editorState ||
            prevProps.contentState !== contentState
        ) {
            extractInlineStyle(newState.editorState);
        }
        if (Object.keys(newState).length) this.setState(newState);
        this.editorProps = this.filterEditorProps(this.props);
        this.customStyleMap = this.getStyleMap(this.props);
    }

    onEditorBlur = () => {
        this.setState({
            editorFocused: false,
        });
    };

    onEditorFocus = event => {
        const { onFocus } = this.props;
        this.setState({
            editorFocused: true,
        });
        const editFocused = this.focusHandler.isEditorFocused();
        if (onFocus && editFocused) {
            onFocus(event);
        }
    };

    onEditorMouseDown = () => {
        this.focusHandler.onEditorMouseDown();
    };

    keyBindingFn = event => {
        if (event.key === 'Tab') {
            const { onTab } = this.props;
            if (!onTab || !onTab(event)) {
                const editorState = changeDepth(
                    this.state.editorState,
                    event.shiftKey ? -1 : 1,
                    4
                );
                if (editorState && editorState !== this.state.editorState) {
                    this.onChange(editorState);
                    event.preventDefault();
                }
            }
            return null;
        }
        if (event.key === 'ArrowUp' || event.key === 'ArrowDown') {
            if (SuggestionHandler.isOpen()) {
                event.preventDefault();
            }
        }
        return getDefaultKeyBinding(event);
    };

    onToolbarFocus = event => {
        const { onFocus } = this.props;
        if (onFocus && this.focusHandler.isToolbarFocused()) {
            onFocus(event);
        }
    };

    onWrapperBlur = event => {
        const { onBlur } = this.props;
        if (onBlur && this.focusHandler.isEditorBlur(event)) {
            onBlur(event, this.getEditorState());
        }
    };

    onChange = editorState => {
        const { readOnly, onEditorStateChange } = this.props;
        if (
            !readOnly &&
            !(
                getSelectedBlocksType(editorState) === 'atomic' &&
                editorState.getSelection().isCollapsed
            )
        ) {
            if (onEditorStateChange) {
                onEditorStateChange(editorState, this.props.wrapperId);
            }
            if (!hasProperty(this.props, 'editorState')) {
                this.setState({ editorState }, this.afterChange(editorState));
            } else {
                this.afterChange(editorState);
            }
        }
    };

    setWrapperReference = ref => {
        this.wrapper = ref;
    };

    setEditorReference = ref => {
        if (this.props.editorRef) {
            this.props.editorRef(ref);
        }
        this.editor = ref;
    };

    getCompositeDecorator = toolbar => {
        const decorators = [
            ...this.props.customDecorators,
            getLinkDecorator({
                showOpenOptionOnHover: toolbar.link.showOpenOptionOnHover,
            }),
        ];
        if (this.props.mention) {
            decorators.push(
                ...getMentionDecorators({
                    ...this.props.mention,
                    onChange: this.onChange,
                    getEditorState: this.getEditorState,
                    getSuggestions: this.getSuggestions,
                    getWrapperRef: this.getWrapperRef,
                    modalHandler: this.modalHandler,
                })
            );
        }
        if (this.props.hashtag) {
            decorators.push(getHashtagDecorator(this.props.hashtag));
        }
        return new CompositeDecorator(decorators);
    };

    getWrapperRef = () => this.wrapper;

    getEditorState = () => this.state ? this.state.editorState : null;

    getSuggestions = () => this.props.mention && this.props.mention.suggestions;

    afterChange = editorState => {
        setTimeout(() => {
            const { onChange, onContentStateChange } = this.props;
            if (onChange) {
                onChange(convertToRaw(editorState.getCurrentContent()));
            }
            if (onContentStateChange) {
                onContentStateChange(convertToRaw(editorState.getCurrentContent()));
            }
        });
    };

    isReadOnly = () => this.props.readOnly;

    isImageAlignmentEnabled = () => this.state.toolbar.image.alignmentEnabled;

    render() {
        const { editorState, editorFocused, toolbar } = this.state;
        const {
            locale,
            localization: { locale: newLocale, translations },
            toolbarCustomButtons,
            toolbarOnFocus,
            toolbarClassName,
            toolbarHidden,
            editorClassName,
            wrapperClassName,
            toolbarStyle,
            editorStyle,
            wrapperStyle,
            uploadCallback,
            ariaLabel,
        } = this.props;

        const controlProps = {
            modalHandler: this.modalHandler,
            editorState,
            onChange: this.onChange,
            translations: {
                ...localeTranslations[locale || newLocale],
                ...translations,
            },
        };
        const toolbarShow =
            editorFocused || this.focusHandler.isInputFocused() || !toolbarOnFocus;
        return (
            <div
                id={this.wrapperId}
                className={classNames(wrapperClassName, 'rdw-editor-wrapper')}
                style={wrapperStyle}
                onClick={this.modalHandler.onEditorClick}
                onBlur={this.onWrapperBlur}
                aria-label="rdw-wrapper"
            >
                {!toolbarHidden && (
                    <div
                        className={classNames('rdw-editor-toolbar', toolbarClassName)}
                        style={{
                            visibility: toolbarShow ? 'visible' : 'hidden',
                            ...toolbarStyle,
                        }}
                        onMouseDown={this.preventDefault}
                        aria-label="rdw-toolbar"
                        aria-hidden={(!editorFocused && toolbarOnFocus).toString()}
                        onFocus={this.onToolbarFocus}
                    >
                        {toolbar.options.map((opt, index) => {
                            const Control = Controls[opt];
                            const config = toolbar[opt];
                            if (opt === 'image' && uploadCallback) {
                                config.uploadCallback = uploadCallback;
                            }
                            return <Control key={index} {...controlProps} config={config} />;
                        })}
                        {toolbarCustomButtons &&
                            toolbarCustomButtons.map((button, index) =>
                                React.cloneElement(button, { key: index, ...controlProps })
                            )}
                    </div>
                )}
                <div
                    ref={this.setWrapperReference}
                    className={classNames(editorClassName, 'rdw-editor-main')}
                    style={editorStyle}
                    onClick={this.focusEditor}
                    onFocus={this.onEditorFocus}
                    onBlur={this.onEditorBlur}
                    onKeyDown={KeyDownHandler.onKeyDown}
                    onMouseDown={this.onEditorMouseDown}
                >
                    <Editor
                        ref={this.setEditorReference}
                        keyBindingFn={this.keyBindingFn}
                        editorState={editorState}
                        onChange={this.onChange}
                        blockStyleFn={blockStyleFn}
                        customStyleMap={this.getStyleMap(this.props)}
                        handleReturn={this.handleReturn}
                        handlePastedText={this.handlePastedTextFn}
                        blockRendererFn={this.blockRendererFn}
                        handleKeyCommand={this.handleKeyCommand}
                        ariaLabel={ariaLabel || 'rdw-editor'}
                        blockRenderMap={blockRenderMap}
                        {...this.editorProps}
                    />
                </div>
            </div>
        );
    }
}

export default WysiwygEditor;
