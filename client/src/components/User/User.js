import React, { useState, useEffect } from 'react';
import './User.scss';

import UserForm from '../UserForm/UserForm';

import { useGlobalContext } from '../../context';

const User = ({ data, color }) => {

	const { deleteUser, editUser, fetchUsers } = useGlobalContext();

	const { id, name, surname, email, birthDate } = data;

	const [isEdit, setIsEdit] = useState(false);

	let style = {
		backgroundColor: color
	};


	const handleEdit = async (data) => {

		let res = await editUser(data, id);

		if (!res) {
			console.log('error in edit');
		} else {
			await fetchUsers();
		}

		setIsEdit(false);
	};

	const handleUndo = () => {
		setIsEdit(false);
	};

	if (!isEdit) {

		return (
			<div className="row" style={style}>
				<div className='user-container'>
					<div className="elem">{name}</div>
					<div className="elem">{surname}</div>
					<div className="elem">{email}</div>
					<div className="elem">{birthDate}</div>
				<div className="btn-container">
					<button type="button" className='btn' onClick={() => { setIsEdit(true) }}>Edit</button>
					<button type="button" className='btn' onClick={() => { deleteUser(id) }}>Delete</button>
				</div>
				</div>
			</div>

		)
	} else {

		return (
			<>
				<UserForm 
					action={handleEdit} 
					initialData={{ name, surname, email, birthDate }}
					cssClass='user-edit'	
				/>
				<button type="button" onClick={handleUndo}>Undo</button>
			</>
		)
	}


}

User.defaultProps = {
	data: {
		name: 'nome',
		surname: 'cognome',
		email: 'email',
		birthDate: 'data',
	}, 
	color : 'transparent'
}

export default User