import React from 'react'
import ControlMenu from './Components/ControlMenu'

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
            <StyledInput type='heading' defaultValue="Общие сведения" draggable />
            <StyledInput type='info' wysiwyg='full' draggable />
            <StyledTableInput type='diagnose' wysiwyg='partial' draggable />
            <Group draggable>
                {/* TODO: Надо завязаться на название болезни и генировать текст автоматически */}
                {/* Должно выглядеть типа: `Подробнее про типы ${data.disease}` */}
                <StyledInput type='info-heading' defaultValue="Подробнее про типы сахарного диабета" />
                <StyledTextArea type='info' wysiwyg='full' />
            </Group>
            <Group draggable>
                <StyledInput type='info-heading' defaultValue="Симптомы и синдромы" />
                {/* TODO: Тут должно прокидываться поле data с сервера */}
                <Symptomes />
            </Group>
            {/* TODO: Тоже надо завязаться на название болезни и генировать текст автоматически */}
            <StyledInput type='heading' defaultValue="Какими лекарствами лечат сахарный диабет" draggable />
            <StyledTable wysiwyg='partial' draggable />
        </div >
    )
}
