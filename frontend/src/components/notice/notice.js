import React from 'react'

export default function Notice({ children, type = "info" }) {
    return (
        <div className={`notice notice-${type}`}>{children}</div>
    )
}
