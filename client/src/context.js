import React, { useContext, useState } from 'react';

import { fetchData } from './module/connectionFunctions';

const baseUrl = process.env.REACT_APP_SERVER;

const AppContext = React.createContext();

export const AppProvider = ({ children }) => {

    const [users, setUsers] = useState([]);

    // Manage modal for message
    const [isOpenModal, setIsOpenModal] = useState(false);
    const [modalMsg, setModalMsg] = useState('prova');

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
	
	const deleteUser = async (id)=>{

        await fetchData('DELETE', baseUrl + 'user?id=' + id, {}).then((res) => {

            const { data, message } = res;

            if (!data && message) {
                setIsOpenModal(true);
                setModalMsg('Errore nell\'eliminazione');

                return false;
                
            } else {
                fetchUsers();
            }
        });
        return true;
	};
	
	const editUser = async (data, id)=>{

        await fetchData('PUT', baseUrl + 'user?id=' + id, data).then((res) => {

            const { data, message } = res;

            if (!data && message) {
                setIsOpenModal(true);
                setModalMsg('Errore nella modifica');

                return false;
            } else {
                fetchUsers();
            }
        });

        return true;
	};

    const createUser = async (data)=>{

        await fetchData('POST', baseUrl + 'user', data).then((res) => {

            const { data, message } = res;

            if (!data && message) {
                setIsOpenModal(true);
                setModalMsg('Errore creazione nuovo utente');

                return false;
            } else {
                fetchUsers();
            }
        });

        return true;
	};

    return <AppContext.Provider value={
        { users, fetchUsers, createUser, editUser, deleteUser,
            modalMsg, setModalMsg, isOpenModal, setIsOpenModal }
    }>
        {children}
    </AppContext.Provider>
};

export const useGlobalContext = () => { return useContext(AppContext); };
