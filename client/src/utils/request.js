const request = async (headers, data, url ) => {
    const options = {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            ...headers,
        },
        body: JSON.stringify({...data})
    }

    const response = await fetch(url, options);

    const result = await response.json();

    return result;
};

export default {
    apiRequest: request,
}