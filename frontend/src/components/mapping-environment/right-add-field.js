import React, { useState } from 'react'

import "./field.sass"
import RightFieldCreator from './right-field-creator';

export default function RightAddField({ schemaObjKey }) {

    const [isAdding, setIsAdding] = useState(false);

    const onClick = () => {
        setIsAdding(true);
    }

    const onCreate = () => {
        setIsAdding(false);
    }

    if (isAdding)
        return <RightFieldCreator
            schemaObjKey={schemaObjKey}
            onCreate={onCreate}
        />


    return (
        <div className='field field__add' onClick={onClick}>
            <svg width="10" height="10" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M4.28571 10V5.71429H0V4.28571H4.28571V0H5.71429V4.28571H10V5.71429H5.71429V10H4.28571Z" fill="black" />
            </svg>
            Add a custom field
        </div>
    )
}
