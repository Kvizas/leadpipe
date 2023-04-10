export async function wpGet(endpoint) {
    const response = await fetch(`/wp-json/leadpipe/v1/` + endpoint);
    const json = await response.json();
    return [response.status, json];
}

export async function wpPost(endpoint, data) {
    const response = await fetch(`/wp-json/leadpipe/v1/` + endpoint, {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify(data),
    });
    const json = await response.json();
    return [response.status, json];
}