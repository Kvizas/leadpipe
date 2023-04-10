import React from 'react'
import FormList from '../form-list/form-list'

export default function PageForms() {
    return (
        <div className=''>
            <h2>All forms</h2>
            <p>
                Choose a form which you want to map to your CRM schema.
            </p>
            <FormList />
        </div>
    )
}
