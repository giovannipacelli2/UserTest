import React, { useState, useEffect } from 'react';
import './User.scss';

import UserForm from '../UserForm/UserForm';
import { IoIosArrowBack } from "react-icons/io";
import { ImBin } from "react-icons/im";
import { GrEdit } from "react-icons/gr";

import { useGlobalContext } from '../../context';

const User = ({ data, cssClass }) => {

	const { deleteUser, editUser, fetchUsers } = useGlobalContext();

	const { id, name, surname, email, birthDate } = data;

	const [isEdit, setIsEdit] = useState(false);

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
			<div className={`row user-row ${cssClass}`}>
				<div className='user-container'>
					<div className="elem">{name}</div>
					<div className="elem">{surname}</div>
					<div className="elem">{email}</div>
					<div className="elem">{birthDate}</div>
				</div>
	
				<div className="btn-container">
					<div className='icon-btn' onClick={() => { setIsEdit(true) }}>
						<GrEdit className='icon cl-primary'/>
					</div>
					<div className='icon-btn' onClick={() => { deleteUser(id) }}>

						<ImBin className='icon cl-danger'/>
					</div>
				</div>

			</div>

		)
	} else {

		return (
			<div className={`row user-row edit-mode ${cssClass}`}>

				<UserForm //classname='user-edit'
					action={handleEdit} 
					initialData={{ name, surname, email, birthDate }}
					cssClass='user-edit'
				/>
				<div className='abs-btn' onClick={handleUndo}>
					<IoIosArrowBack className='icon'/>
				</div>
			</div>
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
	cssClass : ''
}

export default User