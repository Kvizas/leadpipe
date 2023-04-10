import React from 'react'

export default function PageGA4() {
    return (
        <>
            <h2>Google Analytics settings</h2>
            <form action="#">
                <table className='form-table'>
                    <tbody>
                        <tr class="user-first-name-wrap">
                            <th><label for="google_mp_token">Google Measurement Protocol token</label></th>
                            <td><input type="text" name="google_mp_token" id="google_mp_token" value="" class="regular-text"/></td>
                        </tr>
                    </tbody>
                </table>
                <input type="submit" name="submit" id="submit" class="button button-primary" value="Submit"/> 
            </form>
        </>
    )
}
