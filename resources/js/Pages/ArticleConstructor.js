import React from 'react';
import Authenticated from '@/Layouts/Authenticated';

import ConstructorForm from '@/Components/ArticleConstructor/ConstructorForm';
import BlocksSelect from '@/Components/ArticleConstructor/BlocksSelect';

export default function ArticleConstructor(props) {
    return (
        <Authenticated
            auth={{ user: { name: 'Nick', email: 'n1ckjansens@yandex.ru' } }}
            errors={props.errors}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Конструктор статей</h2>}
        >
            <div className="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 flex sm:flex-col lg:flex-row article-constructor">
                {/* Основная форма */}
                <ConstructorForm />
                {/* Список блоков в правой колонке */}
                <BlocksSelect />
            </div>
        </Authenticated>
    );
}
