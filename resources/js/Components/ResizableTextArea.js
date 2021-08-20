import * as React from 'react';
import ResizeObserver from 'rc-resize-observer';
import omit from 'rc-util/lib/omit';
import classNames from 'classnames';
import calculateNodeHeight from './calculateNodeHeight';

class ResizableTextArea extends React.Component {
    nextFrameActionId;

    resizeFrameId;

    constructor(props) {
        super(props);
        this.state = {
            textareaStyles: {},
            resizeStatus: RESIZE_STATUS.NONE,
        };
    }

    textArea;

    saveTextArea = (textArea) => {
        this.textArea = textArea;
    };

    componentDidMount() {
        this.resizeTextarea();
    }

    componentDidUpdate(prevProps) {
        if (prevProps.value !== this.props.value) {
            this.resizeTextarea();
        }
    }

    handleResize = (size) => {
        const { resizeStatus } = this.state;
        const { autoSize, onResize } = this.props;
        if (resizeStatus !== RESIZE_STATUS.NONE) {
            return;
        }

        if (typeof onResize === 'function') {
            onResize(size);
        }
        if (autoSize) {
            this.resizeOnNextFrame();
        }
    };

    resizeOnNextFrame = () => {
        cancelAnimationFrame(this.nextFrameActionId);
        this.nextFrameActionId = requestAnimationFrame(this.resizeTextarea);
    };

    resizeTextarea = () => {
        const { autoSize } = this.props;
        if (!autoSize || !this.textArea) {
            return;
        }
        const { minRows, maxRows } = autoSize;
        const textareaStyles = calculateNodeHeight(
            this.textArea,
            false,
            minRows,
            maxRows,
        );
        this.setState(
            { textareaStyles, resizeStatus: RESIZE_STATUS.RESIZING },
            () => {
                cancelAnimationFrame(this.resizeFrameId);
                this.resizeFrameId = requestAnimationFrame(() => {
                    this.setState({ resizeStatus: RESIZE_STATUS.RESIZED }, () => {
                        this.resizeFrameId = requestAnimationFrame(() => {
                            this.setState({ resizeStatus: RESIZE_STATUS.NONE });
                            this.fixFirefoxAutoScroll();
                        });
                    });
                });
            },
        );
    };

    componentWillUnmount() {
        cancelAnimationFrame(this.nextFrameActionId);
        cancelAnimationFrame(this.resizeFrameId);
    }

    fixFirefoxAutoScroll() {
        try {
            if (document.activeElement === this.textArea) {
                const currentStart = this.textArea.selectionStart;
                const currentEnd = this.textArea.selectionEnd;
                this.textArea.setSelectionRange(currentStart, currentEnd);
            }
        } catch (e) {
        }
    }

    renderTextArea = () => {
        const {
            prefixCls = 'rc-textarea',
            autoSize,
            onResize,
            className,
            disabled,
        } = this.props;
        const { textareaStyles, resizeStatus } = this.state;
        const otherProps = omit(this.props, [
            'prefixCls',
            'onPressEnter',
            'autoSize',
            'defaultValue',
            'onResize',
        ]);
        const cls = classNames(prefixCls, className, {
            [`${prefixCls}-disabled`]: disabled,
        });
        if ('value' in otherProps) {
            otherProps.value = otherProps.value || '';
        }
        const style = {
            ...this.props.style,
            ...textareaStyles,
            ...(resizeStatus === RESIZE_STATUS.RESIZING
                ?
                { overflowX: 'hidden', overflowY: 'hidden' }
                : null),
        };
        return (
            <ResizeObserver
                onResize={this.handleResize}
                disabled={!(autoSize || onResize)}
            >
                <textarea
                    {...otherProps}
                    className={cls}
                    style={style}
                    ref={this.saveTextArea}
                />
            </ResizeObserver>
        );
    };

    render() {
        return this.renderTextArea();
    }
}

export default ResizableTextArea;
