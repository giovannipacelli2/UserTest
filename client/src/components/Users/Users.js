import React, { useEffect } from 'react';
import './Users.scss';

import { useGlobalContext } from '../../context';

// Import components

import User from '../User/User';
import UserForm from '../UserForm/UserForm';

const Users = () => {

    const { users, fetchUsers, createUser } = useGlobalContext();

    // fetch users
    useEffect(() => {
        fetchUsers();
    }, []);

    const handleCreateUser = async (data) => {
		let res = await createUser(data);

		if (!res) {
			console.log('error sending data');
		} else {
			await fetchUsers();
		}
	};

    return (
        <div className="container">
            <div className='users-container'>
                <div className="user-container head">
                    <div className="elem">Nome</div>
                    <div className="elem">Cognome</div>
                    <div className="elem">Email</div>
                    <div className="elem">Data di nascita</div>
                </div>
                {
                    users.map((user, index)=>{
                        let color = '';
                        if (index % 2 == 0) {
                            color = '#FBFBFB';
                        } else {
                            color = '#E0E0E0';
                        }
                        return <User data={user} key={index} color={color}/>
                    })
                }
            </div>

            <div className="new-user-container">
                <h3>Insersci nuovo utente</h3>
                <UserForm action={handleCreateUser} cssClass='new-user-form'/>
            </div>

        </div>
    )
}

export default Users