import React, { useCallback, useState } from 'react'
import { Fragment } from 'react'
import { Menu, Transition } from '@headlessui/react'
import { ChevronDownIcon } from '@heroicons/react/solid'

import './ControlMenu.scss'

function classNames(...classes) {
    return classes.filter(Boolean).join(' ')
}

// TODO: Подумать нужно ли это хранить на сервере или можно оставить прямо так на клиенте
const dropdownItems = [
    'Про Болезнь',
    'Про Симптом',
    'Про Лекарство',
    'Про Остальное',
];

export default function ControlMenu() {
    // TODO: избавиться от useState и завязаться на общий стейт
    const [activeDropdownItem, setActiveDropdownItem] = useState(0)

    const dropdownClickHandler = useCallback((index) => {
        setActiveDropdownItem(index)
    }, [])

    return (
        <div className="article-constructor__constructor-form-menu">
            <Menu as="div" className="relative inline-block text-left article-constructor__constructor-form-menu-dropdown">
                {({ open }) => {
                    return (
                        <>
                            <div>
                                <Menu.Button className="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-1 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-indigo-500">
                                    {dropdownItems[activeDropdownItem]}
                                    <ChevronDownIcon className="-mr-1 ml-2 h-5 w-5" aria-hidden="true" />
                                </Menu.Button>
                            </div>

                            <Transition
                                show={open}
                                as={Fragment}
                                enter="transition ease-out duration-100"
                                enterFrom="transform opacity-0 scale-95"
                                enterTo="transform opacity-100 scale-100"
                                leave="transition ease-in duration-75"
                                leaveFrom="transform opacity-100 scale-100"
                                leaveTo="transform opacity-0 scale-95"
                            >
                                <Menu.Items
                                    static
                                    className="origin-top-right absolute left-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none"
                                >
                                    <div className="py-1">
                                        {dropdownItems.map((item, i) => (
                                            <Menu.Item key={i}>
                                                {({ active }) => (
                                                    <button
                                                        onClick={() => dropdownClickHandler(i)}
                                                        className={classNames(
                                                            active ? 'bg-gray-100 text-gray-900' : 'text-gray-700',
                                                            'block w-full text-left px-4 py-2 text-sm'
                                                        )}
                                                    >
                                                        {item}
                                                    </button>
                                                )}
                                            </Menu.Item>
                                        ))}
                                    </div>
                                </Menu.Items>
                            </Transition>
                        </>
                    )
                }}
            </Menu>

            <button className="ml-1 rounded-md border border-gray-300 shadow-sm px-4 py-1 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-indigo-500 px-3 py-1 rounded-md text-sm font-medium">Сохранить</button>
            <button className="ml-1 rounded-md border border-gray-800 shadow-sm px-4 py-1 bg-red-500 text-white text-sm font-medium hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-indigo-500 px-3 py-1 rounded-md text-sm font-medium">Удалить</button>
            <button className="ml-1 rounded-md border border-gray-300 shadow-sm px-4 py-1 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-indigo-500 px-3 py-1 rounded-md text-sm font-medium">История версий</button>
        </div>
    )
}
