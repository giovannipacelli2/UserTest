import React, { useEffect } from 'react';
import './UserList.scss';

import { useGlobalContext } from '../../context';

// Import components

import User from '../User/User';
import UserForm from '../UserForm/UserForm';

const UserList = () => {

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
        <div className="container-master">
            <div className='user-table'>
                <div className="row head">
                    <div className="elem">Nome</div>
                    <div className="elem">Cognome</div>
                    <div className="elem">Email</div>
                    <div className="elem">Data di nascita</div>
                </div>
                {
                    users.map((user, index)=>{
                        let css = '';
                        if (index % 2 == 0) {
                            css = 'table-light';
                        } else {
                            css = 'table-dark';
                        }
                        return <User data={user} key={index} cssClass={css}/>
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

export default UserList