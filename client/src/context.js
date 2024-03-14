import React, { useContext, useState } from 'react';

import { fetchData } from './module/connectionFunctions';

const baseUrl = process.env.REACT_APP_SERVER;

const AppContext = React.createContext();

export const AppProvider = ({ children }) => {

    const [users, setUsers] = useState([]);

    const fetchUsers = () => {

        fetchData('GET', baseUrl + 'users', {}).then((res) => {

            const { data, message } = res;

            if (!data && message) {
                console.log(message);
                
                setUsers([]);
            } else {
                setUsers(data.users);
            }
        });
    };

    return <AppContext.Provider value={
        { users, fetchUsers }
    }>
        {children}
    </AppContext.Provider>
};

export const useGlobalContext = () => { return useContext(AppContext); };
