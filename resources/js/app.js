require('./bootstrap');

// Import modules...
import React from 'react';
import { render } from 'react-dom';
import { App } from '@inertiajs/inertia-react';
import { InertiaProgress } from '@inertiajs/progress';

import { Provider } from 'react-redux'
import store from './Store'

import { library } from '@fortawesome/fontawesome-svg-core'
import { faHeading, faAlignRight, faList, faCaretDown } from '@fortawesome/free-solid-svg-icons'

library.add(faHeading, faAlignRight, faList, faCaretDown)

const el = document.getElementById('app');

render(
    <Provider store={store}>
        <App initialPage={JSON.parse(el.dataset.page)} resolveComponent={(name) => require(`./Pages/${name}`).default} />,
    </Provider>,
    el
);

InertiaProgress.init({ color: '#4B5563' });
