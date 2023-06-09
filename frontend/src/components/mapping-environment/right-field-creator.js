import React, { useState, useContext, useRef, useEffect } from 'react'

import "./field.sass"
import { SchemaContext } from '../../contexts/schema';

export default function RightFieldCreator({ schemaObjKey, onCreate }) {

    const { addCustomField } = useContext(SchemaContext);

    const labelInput = useRef();

    const [label, setLabel] = useState();

    const onSubmit = () => {
        addCustomField(schemaObjKey, label);
        if (onCreate) onCreate();
    }

    const keypressListener = e => {
        if (e.key === "Enter") onSubmit();
    }

    useEffect(() => {
        if (labelInput.current)
            labelInput.current.addEventListener("keypress", keypressListener);

        return () => {
            labelInput.current.removeEventListener("keypress", keypressListener);
        }
    }, [labelInput, label])


    return <>
        <div class="field__label">Adding: Please insert your new field label</div>
        <input
            onChange={e => setLabel(e.target.value)}
            className='field field__right field__creator'
            autoFocus="true"
            ref={labelInput}
        />
        <div onClick={onSubmit} className="field__edit field__submit">
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M6.66683 9.16601L6.66683 10.8327L10.1668 10.8327L8.8335 12.166L10.0002 13.3327L13.3335 9.99935L10.0002 6.66602L8.8335 7.83268L10.1668 9.16602L6.66683 9.16601ZM1.66683 9.99935C1.66683 8.84657 1.88572 7.76324 2.3235 6.74935C2.76127 5.73546 3.35488 4.85352 4.10433 4.10352C4.85433 3.35352 5.73627 2.7599 6.75016 2.32268C7.76405 1.88546 8.84739 1.66657 10.0002 1.66602C11.1529 1.66602 12.2363 1.8849 13.2502 2.32268C14.2641 2.76046 15.146 3.35407 15.896 4.10352C16.646 4.85352 17.2396 5.73546 17.6768 6.74935C18.1141 7.76324 18.3329 8.84657 18.3335 9.99935C18.3335 11.1521 18.1146 12.2355 17.6768 13.2493C17.2391 14.2632 16.6454 15.1452 15.896 15.8952C15.146 16.6452 14.2641 17.2391 13.2502 17.6768C12.2363 18.1146 11.1529 18.3332 10.0002 18.3327C8.84738 18.3327 7.76405 18.1138 6.75016 17.676C5.73627 17.2382 4.85433 16.6446 4.10433 15.8952C3.35433 15.1452 2.76044 14.2632 2.32266 13.2493C1.88489 12.2355 1.66627 11.1521 1.66683 9.99935Z" fill="black" />
            </svg>
        </div>
    </>
}
