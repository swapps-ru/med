import React from 'react'
import ControlMenu from './Components/ControlMenu'
import StyledInput from '@/Components/StyledInput'
import StyledTextArea from '@/Components/StyledTextArea'
import StyledTableList from '@/Components/StyledTableList'
import StyledSpoiler from '@/Components/StyledSpoiler'

import './ConstructorForm.scss'

export default function ConstructorForm() {
    return (
        // FIXME: Пока перетаскивание не работает, нужно чинить, подозреваю, что эта либа не умеет работать с
        // реактом
        // Придумал, как просто и быстро написать это самому, в процессе
        //
        // TODO: Нужно завязаться на данные про порядок блоков с сервера, пока данные не готовы - хардкожу
        <div className="article-constructor__constructor-form" >
            {/* Верхнее меню управления */}
            < ControlMenu />
            {/* Компоненты конструктора */}
            <StyledInput type='heading' defaultValue="Общие сведения" draggable marginTop={15} />
            <StyledTextArea marginTop={15} type='align-right' defaultValue="Nostrud dolore eiusmod dolore ea incididunt. In Lorem fugiat mollit pariatur ipsum occaecat cupidatat. Et sint aliquip occaecat ad anim ipsum exercitation in tempor mollit ipsum." draggable wysiwyg />
            <StyledTableList marginTop={15} type='list' wysiwyg defaultValue={['Диабет 1ого типа', 'Диабет 2ого типа', 'Некоторые редкие типы']} draggable />
            {/* TODO: Надо завязаться на название болезни и генировать текст автоматически */}
            {/* Должно выглядеть типа: `Подробнее про типы ${data.disease}` */}
            <StyledSpoiler marginTop={15} type='caret-down' defaultValue={['Диабет 1ого типа', 'Nostrud dolore eiusmod dolore ea incididunt. In Lorem fugiat mollit pariatur ipsum occaecat cupidatat. Et sint aliquip occaecat ad anim ipsum exercitation in tempor mollit ipsum.']} draggable />
            {/* <StyledInput type='info-heading' defaultValue="Симптомы и синдромы" /> */}
            {/* TODO: Тут должно прокидываться поле data с сервера */}
            {/* <Symptomes /> */}
            {/* </Group> */}
            {/* TODO: Тоже надо завязаться на название болезни и генировать текст автоматически */}
            <StyledInput type='heading' defaultValue="Какими лекарствами лечат сахарный диабет" draggable marginTop={15} />
            {/* <StyledTable wysiwyg='partial' draggable /> */}
        </div >
    )
}
