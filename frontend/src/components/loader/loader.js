import React from 'react'

import "./loader.scss"

export default function Loader() {
    return (
        <div style={{ display: "flex", padding: "16px", height: "100%" }}>
            <div className='loader'></div>
        </div>
    )
}
